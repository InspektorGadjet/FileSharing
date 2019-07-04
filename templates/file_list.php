{% extends 'layout.php' %}

{% block content %}
	<div class="info">
		<p>100 последних загруженных файлов</p>
	</div>
	{% for item in fileList %}

	<div class="list">
		<div class="block">
			<div style="margin-right: 200px; margin: auto;">
				<label>
					Файл: {{ item.realName }} 
				</label>
				<a class="btn" href="/view/{{ item.serverName }}">Обзор</a>
				<br>
				<nav><a class="btn" href="/download/{{ item.serverName }}">Скачать</a>
				{% if item.author == true %}
					<a class="btn" href="/delete/{{ item.serverName }}">Удалить</a></nav>
				{% endif %}
			</div>
		</div>
	</div>
	{% endfor %}
{% endblock %}