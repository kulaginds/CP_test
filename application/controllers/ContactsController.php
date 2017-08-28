<? if (!defined('APP')) exit('Hack attempt!');

require_once SYS . '/controllers/controller.php';
require_once APP . '/models/contact.php';
require_once CONF . '/pagination.php';
require_once CONF . '/upload.php';

class ContactsController extends Controller
{
	public function action_index() {
		global $pagination_per_page, $upload_directory;

		$sort = $this->getQueryParam('sort');
		$page = $this->getQueryParam('page', 0);
		$page = ($page > 0) ? $page : 1;

		// pagination
		$limit      = $pagination_per_page;
		$skip       = ($page - 1) * $limit;
		$count      = Contact::count_all(); // Count of all available posts
		$pagination = (object)array(
			'needed'        => $count > $limit,
            'count'         => $count,
            'page'          => $page,
            'lastpage'      => (ceil($count / $limit) == 0 ? 1 : ceil($count / $limit)),
            'limit'         => $limit,
		);

		switch ($sort) {
			case 'first_name_asc':
				$field     = 'first_name';
				$direction = 'asc';
				break;
			case 'first_name_desc':
				$field     = 'first_name';
				$direction = 'desc';
				break;
			case 'last_name_asc':
				$field     = 'last_name';
				$direction = 'asc';
				break;
			case 'last_name_desc':
				$field     = 'last_name';
				$direction = 'desc';
				break;
			case 'id_desc':
				$field     = 'id';
				$direction = 'desc';
				break;
			default:
				$sort      = 'id_asc';
				$field     = 'id';
				$direction = 'asc';
				break;
		}

		$contacts = Contact::all_order_by_limit($field, $direction, $skip, $limit);

		return $this->view->render('page/index.php', array(
			'item_name'        => 'contact_list',
			'contacts'         => $contacts,
			'upload_directory' => $upload_directory,
			'sort'             => $sort,
			'pagination'       => $pagination,
		));
	}

	public function action_create() {
		global $upload_directory, $max_upload_size;

		$params = array(
			'item_name'        => 'add_contact',
			'max_upload_size'  => $max_upload_size,
			'errors'           => array(),
			'success'          => false,
		);

		if ($this->request_method == 'POST') {
			$inputs = array(
				'first_name' => trim(strip_tags($this->getParsedBodyParam('first_name', ''))),
				'last_name'  => trim(strip_tags($this->getParsedBodyParam('last_name', ''))),
				'email'      => trim(strip_tags($this->getParsedBodyParam('email', ''))),
				'phone'      => trim(strip_tags($this->getParsedBodyParam('phone', ''))),
				'comment'    => trim(strip_tags($this->getParsedBodyParam('comment', ''))),
			);

			if (empty($inputs['comment'])) {
				unset($inputs['comment']);
			}

			$photo = $this->uploadFile('photo');

			if (false !== $photo) {
				if (is_array($photo) && array_key_exists('error', $photo)) {
					$params['errors']['photo'][] = $photo['error'];
				} else {
					$inputs['photo'] = $photo;
				}
			}

			if (count($params['errors']) == 0) {
				$contact = Contact::factory($inputs);

				try {
					if ($contact->save()) {
						$params['success'] = true;
						$inputs = array();
					} else {
						$params['errors'] = array_merge($params['errors'], $contact->errors);
					}
				} catch (PDOException $e) {
					if (false !== strpos($e->getMessage(), '1062 Duplicate entry')) {
						$params['error'] = 'Контакт с введенными e-mail и телефоном уже сущетвует.';
					} else {
						$params['error'] = 'Произошла ошибка при добавлении записи в базу данных.';

						if (DEBUG) {
							$params['error'] .= '<br>';
							$params['error'] .= 'Контроллер: ' . get_class($this) . '<br>';
							$params['error'] .= 'Действие: action_create<br>';
							$params['error'] .= 'Поля:<br>' . var_export($inputs);
						}
					}
				}
			}

			$params = array_merge($params, $inputs);
		}

		return $this->view->render('page/create.php', $params);
	}
}

