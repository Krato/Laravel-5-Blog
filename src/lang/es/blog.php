<?php

return [
	
	'title'	=>	'Blog',
	'menu'	=>	array(
		'view'		=>	'Ver entradas',
		'create' 	=>	'Crear entrada',
		'categories'=>	'Categorías',
	),
	'states'	=>	array(
		'all'		=>	'Todas',
		'draft'		=>	'Borrador',
		'published'	=>	'Publicado',
		'trash'		=>	'Papelera',
	),
	'tables'	=>	array(
		'photo'			=>	'Imágen Destacada',
		'title'			=>	'Título',
		'description'	=>	'Descripción',
		'tags'			=>	'Etiquetas',
		'publish_date'	=>	'Fecha de Publicación',
		'state'			=>	'Estado',
		'options'		=>	'Opciones',
	),
	'categories'	=>	array(
		'add'			=>	'Añadir Categoría',
		'update'		=>	'Actualizar Categoría',
		'name'			=>	'Nombre',
		'sure_delete'	=>	'¿Estás seguro de eliminar ésta categoría?'
	),
	'slug'	=>	array(
		'permalink'	=>	'Enlace Permanente',
		'change'	=>	'Cambia el enlace permanente',
	),
	'actions'	=>	array(
		'yes'			=>	'Si',
		'no'			=>	'No',
		'save'			=>	'Guardar',
		'edit'			=>	'Editar',
		'update'		=>	'Actualizar',
		'delete'		=>	'Eliminar',
		'cancel'		=>	'Cancelar',
		'choose_image'	=>	'Elegir Imagen',
	),
	'database'	=>	array(
		'post_saved'	=>	'Entrada guardada',
		'post_updated'	=>	'Entrada actualizada',
		'post_trash'	=>	'Entrada enviada a la papelera',
		'post_restored'	=>	'Entrada restaurada',
		'post_deleted'	=>	'Entrada eliminada',
		'post_trash_ask'=>	'¿Estás seguro de eliminar esta entrada? Será enviada a la papelera',
		'post_ask'		=>	'¿Estás seguro de eliminar esta entrada? Será eliminada de la BD',
		'category_saved'	=>	'Categoría guardada',
		'category_updated'	=>	'Categoría actualizada',
		'category_deleted'	=>	'Categoría eliminada',
		'error'			=>	'Error en la BD',
	),

];
