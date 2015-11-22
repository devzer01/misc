<?php
$field_classes = array('votes' => array(
											'class' => 'voteTotal', 
											'element' => 'div', 
											'db' => 'total_votes'
	),
						   'uri' => array(
											'class' => 'ideaTitle userContent', 
											'element' => 'span',
											'suffix'  => 'a', 
											'attr' => 'href',
											'db' => 'uri'
	), 
						   'title' => array(
											'class' => 'ideaTitle userContent', 
											'element' => 'span',
											'suffix'  => 'a', 
											'db' => 'title'
	),
						'comment_count' => array(
											'class' => 'ideaComments',
											'element' => 'span',
											'db'     => 'comment_count'
	),
						'username_expert' => array(
											'class' => 'baseUserExpert IdeaOtherContents',
											'element' => 'a',
											'suffix'  => 'span',
											'db' => 'username_expert'
	), 
						'username' => array(
											'class' => 'baseUserNormal IdeaOtherContents',
											'element' => 'a',
											'suffix'  => 'span',
											'db' => 'username'
	), 
						'user_uri' => array(
											'class'   => 'baseUserExpert IdeaOtherContents',
											'db'      => 'username_uri',
											'element' => 'a',
											'attr'    => 'href'
	),
						'user_uri' => array(
											'class'   => 'baseUserNormal IdeaOtherContents',
											'db'      => 'username_uri',
											'element' => 'a',
											'attr'    => 'href'
	),
						'category' => array(
											'class' => 'ideaCategories',
											'db'    => 'category',
											'element' => 'span'
	),
						'created' => array(
											'class' => 'commenterDate',
											'db'      => 'created',
											'element' => 'span',
											'istime'  => 1,
	), 
						'status'  => array(
											'class' => 'ideaStatusContents',
											'db'    => 'status',
											'element' => 'span'
	),
						'text' => array(
											'class' => 'baseIdeaBody',
											'element' => 'span',
											'db' => 'idea_text'
	)
	);