{% extends 'layout.php' %}
{% block content %}
<div class="upload_form">
	<h2>Перетащите файл</h2>
	<input type="hidden" name="MAX_FILE_SIZE" value="30000">
	<form action="/upload" method="post" enctype="multipart/form-data">
		<div style="margin-bottom: 1em;">
	        <div class="file-well">
	            <input type="file" name="newfile">
	        </div>
	    </div>
		<input class="btn-primary" type="submit" name="Отправить"><br>
	</form>
</div>
{% endblock %}
