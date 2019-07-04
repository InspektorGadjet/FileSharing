{% extends 'layout.php' %}
{% block content %}
	
	<div class="info-file">
		<h3>Файл: {{ info.name }}</h3>
		<h3>Размер: {{ info.filesize }}</h3>
		<h3>Дата добавления: {{ info.date }}</h3>
		{% if info.extension == 'jpg' %}
			<img class="file-copy" src="../copyes/{{ info.copy }}">
		{% elseif info.extension == 'png' %}
			<img class="file-copy" src="../copyes/{{ info.copy }}">
		{% endif %}

		<form action="/view/{{ info.serverName }}" method="post">
			<p><h2>Комментарий автора:</h2></p>
			<p>
				<textarea
					{% if info.author == false %}
						readonly
					{% endif %}
					maxlength="100" rows="5" cols="68" name="text"
					>{{ info.comment }}</textarea>
			</p>
				
			<p>
				{% if info.author == true %}
					<input type="submit" name="Обновить" value="Обновить">
				{% endif %}
			</p>
		</form>
		<div class="download-link">
			<a class="butn" href="/download/{{ info.serverName }}">Скачать</a>
		</div>
	</div>
	
{% endblock %}