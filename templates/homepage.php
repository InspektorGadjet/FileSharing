{% extends 'layout.php' %}
{% block content %}
<div class="upload_form">
	<h2>Загрузка файлов</h2>
	<input type="hidden" name="MAX_FILE_SIZE" value="30000">
	<form action="/upload" method="post" enctype="multipart/form-data">
		<input class="btn-primary" type="file" name="newfile"><br>
		<input class="btn-primary" type="submit" name="Отправить"><br>
	</form>
</div>
{% endblock %}
