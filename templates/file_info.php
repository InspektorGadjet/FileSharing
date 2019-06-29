{% extends 'layout.php' %}
{% block content %}
	<div>
		<div  class="info-file">
			<h3>Файл: {{ info.name }}</h3>
			<h3>Размер: {{ info.filesize }}</h3>
			<h3>Дата добавления: {{ info.date }}</h3>
			<a class="btn" href="/download/{{ info.filename }}">Скачать</a>
		</div>
	</div>
{% endblock %}