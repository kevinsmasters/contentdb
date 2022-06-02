<?php

// DataTables PHP library
include( "DataTables.php" );

// Alias Editor classes so they are easy to use
use
	DataTables\Editor,
	DataTables\Editor\Field,
	DataTables\Editor\Format,
	DataTables\Editor\Mjoin,
	DataTables\Editor\Options,
	DataTables\Editor\Upload,
	DataTables\Editor\Validate,
	DataTables\Editor\ValidateOptions;

	// Allow a number of different formats to be submitted for the various demos
	$format = isset( $_GET['format'] ) ?
		$_GET['format'] :
		'';

	if ( $format === 'custom' ) {
		$update = 'n/j/Y';
		$registered = 'l j F Y';
	}
	else {
		$update = "Y-m-d";
		$registered = "Y-m-d";
	}

/*
 * Example PHP implementation used for the join.html example
 */
Editor::inst( $db, 'matrix' )
	->field(
		Field::inst( 'matrix.type' )
			->options( Options::inst()
				->table( 'type' )
				->value( 'id' )
				->label( 'name' )
			)
			->validator( Validate::dbValues() ),
		Field::inst( 'type.name' ),
		Field::inst( 'matrix.title' ),
		Field::inst( 'matrix.url' ),
		Field::inst( 'matrix.author' ),
		Field::inst( 'matrix.content_url' ),
		Field::inst( 'matrix.a4_url' ),
		Field::inst( 'matrix.pub_date' )
				->validator( Validate::dateFormat(
					$update,
					ValidateOptions::inst()
						->allowEmpty( false )
				) )
				->getFormatter( Format::dateSqlToFormat( $update ) )
				->setFormatter( Format::dateFormatToSql( $update ) ),
		Field::inst( 'matrix.category' )
			->options( Options::inst()
				->table( 'category' )
				->value( 'id' )
				->label( 'name' )
			)
			->setFormatter( Format::ifEmpty( NULL ) )
			->validator( Validate::dbValues() ),
		Field::inst( 'category.name' ),
		Field::inst( 'matrix.model' )
			->options( Options::inst()
				->table( 'models' )
				->value( 'id' )
				->label( 'name' )
			)
			->validator( Validate::dbValues() ),
		Field::inst( 'models.name' ),Field::inst( 'matrix.image' )
			->setFormatter( Format::ifEmpty( null ) )
			->upload( Upload::inst( $_SERVER['DOCUMENT_ROOT'].'/matrix/uploads/__NAME__' )
				->db( 'files', 'id', array(
					'filename'    => Upload::DB_FILE_NAME,
					'filesize'    => Upload::DB_FILE_SIZE,
					'web_path'    => Upload::DB_WEB_PATH,
					'system_path' => Upload::DB_SYSTEM_PATH
				) )
				->dbClean( function ( $data ) {
                // Remove the files from the file system
                for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
                    unlink( $data[$i]['system_path'] );
                }

                // Have Editor remove the rows from the database
                return true;
            	} )
				->validator( Validate::fileSize( 50000000, 'Files must be smaller that 50MB' ) )
				->validator( Validate::fileExtensions( array( 'pdf' ), "Please upload an pdf" ) ),
		Field::inst( 'matrix.mtid' )->setFormatter( Format::ifEmpty( NULL ) )
		 )
	)
	->leftJoin( 'type', 'type.id', '=', 'matrix.type' )
	->leftJoin( 'files', 'files.id', '=', 'matrix.image' )
	->leftJoin( 'category', 'category.id', '=', 'matrix.category' )
	->leftJoin( 'models', 'models.id', '=', 'matrix.model' )
	->join(
        Mjoin::inst( 'tag' )
            ->link( 'matrix.id', 'matrix_tag.matrix_id' )
            ->link( 'tag.id', 'matrix_tag.tag_id' )
            ->order( 'name asc' )
            ->fields(
                Field::inst( 'id' )
                    ->validator( Validate::required() )
                    ->options( Options::inst()
                        ->table( 'tag' )
                        ->value( 'id' )
                        ->label( 'name' )
                    ),
                Field::inst( 'name' )
            )
    )
		->join(
	        Mjoin::inst( 'models' )
	            ->link( 'matrix.id', 'matrix_model.matrix_id' )
	            ->link( 'model.id', 'matrix_model.model_id' )
	            ->order( 'name asc' )
	            ->fields(
	                Field::inst( 'id' )
	                    ->validator( Validate::required() )
	                    ->options( Options::inst()
	                        ->table( 'models' )
	                        ->value( 'id' )
	                        ->label( 'name' )
	                    ),
	                Field::inst( 'name' )
	            )
	    )
	->process($_POST)
	->json();
