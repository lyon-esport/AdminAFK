function display_name_file(object, label_id) 
{
    var path = document.getElementById(object.id).value;
	var name_files = path.split("\\");
	document.getElementById(label_id).innerHTML = name_files[2];
}