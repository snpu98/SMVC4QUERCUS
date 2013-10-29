$.fn.makeList = function( _PKName, _rows)
{
	var entry0 = this.find( "[name=idx]"   ).clone();

	this.find( "[name=idx]"   ).remove();

	if( _rows.size()>0)
	{
		this.find( "[name=noRows]").remove();

		for( i in _rows)
		{
			var idx   = rows[i][_PKName];
			var entry = entry0.clone();

			entry.attr( "value", idx);

			for( key in row)
			{
				var val = row[k];

				entry.find( "[name=" +key+ "]").html( val);
			}

			this.append( entry);
		}
	}
};

// (ex) _rows = { { "val" : "a", "txt" : "Choice A" },,,, }
$.fn.makeSelect = function( _rows)
{
	opt0 = this.children( "option").eq( 0).clone();

	this.children( "option").remove();

	if( _rows.size()>0)
	{
		for( i in _rows)
		{
			var opt = opt0.clone();

			opt.attr( "value", _rows[i]["val"]);
			opt.html( _rows[i]["txt"]);

			this.append( opt);
		}
	}
};


json2dom = function( _json)
{
	for( key in _json)
	{
		var val = _json[key];

		var sel = $( "[name=" +key+ "]").get( 0).tagName.toLowerCase() + $( "[name=" +key+ "]").attr( "type").toLowerCase();

		switch( sel)
		{
			case "input"  + "radio"    :
				$( "[name=" +key+ "][val=" +val+ "]").prop( "checked", true);
				break;

			case "input"  + "checkbox" :
				$( "[name=" +key+ "][val=" +val+ "]").prop( "checked", true);
				break;

			case "input"  + "text"     :
				$( "[name=" +key+ "]").val( val);
				break;

			case "input"  + "textarea" :
				$( "[name=" +key+ "]").html( val);
				break;

			case "select" + "":
				$( "[name=" +key+ "]").val( val);
				break;

			default :
				$( "[name=" +key+ "]").html( val);
				break;
		}
	}
};