<? if (!defined('APP')) exit('Hack attempt!'); ?><div class="row">
	<div class="col-md-7">
		<h1>Мои контакты</h1>
	</div>
	<div class="col-md-5">
		<div class="dropdown text-right">
			<button class="btn btn-secondary dropdown-toggle" type="button" id="sortMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Сортировка по
				<? switch ($sort):
					case 'id_desc':
						echo 'номеру 9-1';
					break;
					case 'first_name_asc':
						echo 'имени А-Я';
					break;
					case 'first_name_desc':
						echo 'имени Я-А';
					break;
					case 'last_name_asc':
						echo 'фамилии А-Я';
					break;
					case 'last_name_desc':
						echo 'фамилии Я-А';
					break;
					default:
						echo 'номеру 1-9';
				endswitch; ?>
			</button>
			<div class="dropdown-menu" aria-labelledby="sortMenuButton">
				<a class="dropdown-item<?if ($sort == 'id_asc'):?> active<? endif; ?>" href="<?=$this->addPathParams('/', array('sort' => 'id_asc' ))?>">номеру 1-9</a>
				<a class="dropdown-item<?if ($sort == 'id_desc'):?> active<? endif; ?>" href="<?=$this->addPathParams('/', array('sort' => 'id_desc' ))?>">номеру 9-1</a>
				<a class="dropdown-item<?if ($sort == 'first_name_asc'):?> active<? endif; ?>" href="<?=$this->addPathParams('/', array('sort' => 'first_name_asc' ))?>">имени А-Я</a>
				<a class="dropdown-item<?if ($sort == 'first_name_desc'):?> active<? endif; ?>" href="<?=$this->addPathParams('/', array('sort' => 'first_name_desc' ))?>">имени Я-А</a>
				<a class="dropdown-item<?if ($sort == 'last_name_asc'):?> active<? endif; ?>" href="<?=$this->addPathParams('/', array('sort' => 'last_name_asc' ))?>">фамилии А-Я</a>
				<a class="dropdown-item<?if ($sort == 'last_name_desc'):?> active<? endif; ?>" href="<?=$this->addPathParams('/', array('sort' => 'last_name_desc' ))?>">фамилии Я-А</a>
			</div>
		</div>
	</div>
</div>

<br>
<? if (count($contacts) > 0): ?>
<? foreach ($contacts as $contact): ?>
<div class="card">
	<? if (empty($contact->photo)): ?>
	<img src="http://vk.com/images/deactivated_400.gif" alt="<?=$contact->first_name.' '.$contact->last_name?>" class="card-img-top">
	<? else: ?>
	<img src="<?=$upload_directory . DIRECTORY_SEPARATOR . $contact->photo?>" alt="<?=$contact->first_name.' '.$contact->last_name?>" class="card-img-top">
	<? endif; ?>
	<div class="card-body">
		<h4 class="card-title">#<?=$contact->id.' '.$contact->first_name.' '.$contact->last_name?></h4>
		<p><b>E-mail</b>: <?=$contact->email?></p>
		<p><b>Телефон</b>: <?=$contact->phone?></p>
		<p><b>Комментарий</b>:</p>
		<? if (empty($contact->comment)): ?>
		<small><i>Нет</i></small>
		<? else: ?>
		<pre><?=$contact->comment?></pre>
		<? endif; ?>
	</div>
</div>
<? endforeach; ?>
<? else: ?>
<p>Нет контактов.</p>
<? endif; ?>

<br>

<? if ($pagination->needed): ?>
	<nav>
		<ul class="pagination">
		<? foreach (range(1, $pagination->lastpage) as $i): ?>
            <li class="page-item<? if ($i == $pagination->page): ?> active<? endif; ?>"><a class="page-link" href="<?=$this->addPathParams('/', array('sort' => $sort, 'page' => $i ))?>"><?=$i?></a></li>
        <? endforeach; ?>
		</ul>
	</nav>
<? endif; ?>
