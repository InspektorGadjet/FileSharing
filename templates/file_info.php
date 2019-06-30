{% extends 'layout.php' %}
{% block content %}
	<div>
		<div  class="info-file">
			<h3>Файл: {{ info.name }}</h3>
			<h3>Размер: {{ info.filesize }}</h3>
			<h3>Дата добавления: {{ info.date }}</h3>
			{% if info.extension == 'jpg' %}
				<img src="../copyes/{{ info.copy }}">
			{% elseif info.extension == 'png' %}
				<img src="../copyes/{{ info.copy }}">
			{% endif %}

			<form action="/view/{{ info.server_name }}" method="post">
				<p><h2>Комментарий автора:</h2></p>
				<p><textarea rows="5" cols="68" name="text">{{ comment.text }}</textarea></p>
				<p><input type="submit" name="Обновить"></p>
			</form>

			<a class="btn" href="/download/{{ info.server_name }}">Скачать</a>
		</div>
	</div>
{% endblock %}