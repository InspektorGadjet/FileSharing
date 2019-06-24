{% extends 'header_footer.php' %}

{% block content %}
	{% for item in fileList %}
		<div class="block">
			<div style="margin-right: 200px; margin: auto;">
				<label>
					Имя Файла: {{ item.real_name }} 
				</label>
			<a href="/download/{{ item.server_name }}"  style="color: black; margin-left: 150px;">Скачать</a>
			</div>
		</div>
	{% endfor %}
{% endblock %}