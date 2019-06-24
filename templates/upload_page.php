{% extends 'header_footer.php' %}
{% block content %}
	<div>
		<h1>Имя файла</h1>
		<h2>{{ file_name }}</h2>
		<h1>Тип файла</h1>
		<h2>{{ mime_type }}</h2>
	</div>
	<a href="download/{{ server_name }}">Загрузить</a>
{% endblock %}