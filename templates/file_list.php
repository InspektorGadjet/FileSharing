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
					Файл: {{ item.real_name }} 
				</label>
				<a class="btn" href="/view/{{ item.server_name }}">Обзор</a>
				<br>
				<a class="btn" href="/download/{{ item.server_name }}">Скачать</a>
			</div>
		</div>
	</div>
	{% endfor %}
{% endblock %}