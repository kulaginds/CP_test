<? if (!defined('APP')) exit('Hack attempt!'); ?><h1>Добавить новый контакт</h1>

<? if ($success): ?>
<div class="alert alert-success" role="alert">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
	Контакт успешно добавлен!
</div>
<? endif; ?>
<? if (!empty($error)): ?>
<div class="alert alert-danger" role="alert">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
	<?=$error?>
</div>
<? endif; ?>

<form action="/create" method="POST" enctype="multipart/form-data">
	<input type="hidden" name="MAX_UPLOAD_SIZE" value="<?=$max_upload_size?>">
	<div class="form-group">
		<label for="first_name">Имя * :</label>
		<input type="text" class="form-control" id="first_name" name="first_name" value="<?=$first_name?>" required>
		<? if (array_key_exists('first_name', $errors)): ?>
		<small class="form-text text-danger">
		<? foreach ($errors['first_name'] as $error): ?>
			<?=$error?><br>
		<? endforeach; ?>
		</small>
		<? endif; ?>
	</div>
	<div class="form-group">
		<label for="last_name">Фамилия * :</label>
		<input type="text" class="form-control" id="last_name" name="last_name" value="<?=$last_name?>" required>
		<? if (array_key_exists('last_name', $errors)): ?>
		<small class="form-text text-danger">
		<? foreach ($errors['last_name'] as $error): ?>
			<?=$error?><br>
		<? endforeach; ?>
		</small>
		<? endif; ?>
	</div>
	<div class="form-group">
		<label for="email">E-mail * :</label>
		<input type="email" class="form-control" id="email" name="email" value="<?=$email?>" required>
		<? if (array_key_exists('email', $errors)): ?>
		<small class="form-text text-danger">
		<? foreach ($errors['email'] as $error): ?>
			<?=$error?><br>
		<? endforeach; ?>
		</small>
		<? endif; ?>
	</div>
	<div class="form-group">
		<label for="phone">Телефон * :</label>
		<input type="text" class="form-control" id="phone" name="phone" value="<?=$phone?>" required>
		<small class="form-text text-muted">Например: +74991234567</small>
		<? if (array_key_exists('phone', $errors)): ?>
		<small class="form-text text-danger">
		<? foreach ($errors['phone'] as $error): ?>
			<?=$error?><br>
		<? endforeach; ?>
		</small>
		<? endif; ?>
	</div>
	<div class="form-group">
		<label for="avatar">Фото:</label>
		<input type="file" class="form-control-file" id="photo" name="photo">
		<small class="form-text text-muted">Размер не более 100кб, формат: .jpg, .png, .gif</small>
		<? if (array_key_exists('photo', $errors)): ?>
		<small class="form-text text-danger">
		<? foreach ($errors['photo'] as $error): ?>
		    <?=$error?><br>
		<? endforeach; ?>
		</small>
		<? endif; ?>
	</div>
	<div class="form-group">
		<label for="comment">Комментарий:</label>
		<textarea class="form-control" rows="3" id="comment" name="comment"><?=$comment?></textarea>
	</div>
	<button type="submit" class="btn btn-primary">Добавить</button>
</form>

<br>