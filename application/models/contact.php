<? if (!defined('APP')) exit('Hack attempt!');

require_once SYS . '/models/model.php';

class Contact extends Model
{
	static public function table() {
		return 'contact';
	}

	static public function fields() {
		return array('id', 'first_name', 'last_name', 'email', 'phone', 'photo', 'comment');
	}

	static public function factory($data) {
		return new Contact($data);
	}

	public function valid() {
		return $this->validateFirstName() &&
				$this->validateLastName() &&
				$this->validateEmail() &&
				$this->validatePhone();
	}

	public function validateFirstName() {
		$result = true;

		$result = $result && $this->validAlpha('first_name', $this->first_name);
		$result = $result && $this->validNoWhitespace('first_name', $this->first_name);
		$result = $result && $this->validNoEmpty('first_name', $this->first_name);
		$result = $result && $this->validMax('first_name', $this->first_name, 30, true);

		return $result;
	}

	public function validateLastName() {
		$result = true;

		$result = $result && $this->validAlpha('last_name', $this->last_name);
		$result = $result && $this->validNoWhitespace('last_name', $this->last_name);
		$result = $result && $this->validNoEmpty('last_name', $this->last_name);
		$result = $result && $this->validMax('last_name', $this->last_name, 30, true);

		return true;
	}

	public function validateEmail() {
		return $this->validEmail('email', $this->email);
	}

	public function validatePhone() {
		return $this->validPhone('phone', $this->phone);
	}
}
