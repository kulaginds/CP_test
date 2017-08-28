<? if (!defined('APP')) exit('Hack attempt!'); ?><!DOCTYPE html>
<html lang="ru">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
		<link rel="stylesheet" href="http://getbootstrap.com/docs/4.0/examples/narrow-jumbotron/narrow-jumbotron.css">

		<title>Записная книжонка</title>
	</head>
	<body>
		<div class="container">
			<div class="header clearfix">
				<nav>
					<ul class="nav nav-pills float-right">
						<li class="nav-item">
							<a class="nav-link<? if ($item_name == 'contact_list'): ?> active<? endif; ?>" href="/">Мои контакты</a>
						</li>
						<li class="nav-item">
							<a class="nav-link<? if ($item_name == 'add_contact'): ?> active<? endif; ?>" href="/create">Добавить контакт</a>
						</li>
					</ul>
				</nav>
				<h3 class="text-muted">Записная книжонка</h3>
			</div>
