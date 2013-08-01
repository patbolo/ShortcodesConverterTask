<?php


class ShortcodesConverterTask extends BuildTask {
	
	public function run($request) {
		if (!(DB::getConn() instanceof MySQLDatabase)){
			user_error('Sorry this task only runs on Mysql for the moment');
		}
		if (Director::is_cli() || Permission::check('ADMIN')){
			$dataClasses = ClassInfo::subclassesFor('DataObject');
			$htmlTextClasses = ClassInfo::subclassesFor('HTMLText');
			array_shift($dataClasses);
			foreach($dataClasses as $dataClass){
				$fields = DataObject::custom_database_fields($dataClass);
				echo "\n Inspecting class ".$dataClass."\n";
				foreach($fields as $fieldName=>$fieldType){
					if (in_array($fieldType, $htmlTextClasses)){
						echo "Field ".$fieldName." is of type HTMLText (or descendant) and is now converted\n";
						
						DB::query("UPDATE \"$dataClass\" SET \"$fieldName\" = REPLACE(\"$fieldName\", '[sitetree_link%20', '[sitetree_link,')"); 
						DB::query("UPDATE \"$dataClass\" SET \"$fieldName\" = REPLACE(\"$fieldName\", '[sitetree_link ', '[sitetree_link,')");
						DB::query("UPDATE \"$dataClass\" SET \"$fieldName\" = REPLACE(\"$fieldName\", '[document_link%20', '[document_link,')"); 
						DB::query("UPDATE \"$dataClass\" SET \"$fieldName\" = REPLACE(\"$fieldName\", '[document_link ', '[document_link,')"); 
						if (Object::has_extension($dataClass, 'Versioned')){
							echo "Class ".$dataClass." is versioned; converting live and versioned records too\n";
							DB::query("UPDATE \"{$dataClass}_Live\" SET \"$fieldName\" = REPLACE(\"$fieldName\", '[sitetree_link%20', '[sitetree_link,')"); 
							DB::query("UPDATE \"{$dataClass}_Live\" SET \"$fieldName\" = REPLACE(\"$fieldName\", '[sitetree_link ', '[sitetree_link,')"); 
							DB::query("UPDATE \"{$dataClass}_Live\" SET \"$fieldName\" = REPLACE(\"$fieldName\", '[document_link%20', '[document_link,')"); 
							DB::query("UPDATE \"{$dataClass}_Live\" SET \"$fieldName\" = REPLACE(\"$fieldName\", '[document_link ', '[document_link,')");
							DB::query("UPDATE \"{$dataClass}_versions\" SET \"$fieldName\" = REPLACE(\"$fieldName\", '[sitetree_link%20', '[sitetree_link,')"); 
							DB::query("UPDATE \"{$dataClass}_versions\" SET \"$fieldName\" = REPLACE(\"$fieldName\", '[sitetree_link ', '[sitetree_link,')");
							DB::query("UPDATE \"{$dataClass}_versions\" SET \"$fieldName\" = REPLACE(\"$fieldName\", '[document_link%20', '[document_link,')"); 
							DB::query("UPDATE \"{$dataClass}_versions\" SET \"$fieldName\" = REPLACE(\"$fieldName\", '[document_link ', '[document_link,')"); 
						}
					}
				}
			}
		}
	}
}
