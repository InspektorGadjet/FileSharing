{% extends 'header_footer.php' %}
{% block content %}
<div class="upload_form">
	<h2>Загрузка файлов</h2>
	<input type="hidden" name="MAX_FILE_SIZE" value="30000">
	<form action="/upload" method="post" enctype="multipart/form-data">
		<input type="file" name="newfile" id="file"><br>
		<input type="submit" name="Отправить"><br>
	</form>
</div>
{% endblock %}
