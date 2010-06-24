// JavaScript Document


var page = "./upgrader.php";

function import_users()
{
	$("#the_results").html("<strong>Step 2/6 - Importing Users , Please wait while ClipBucket imports users....</strong>");
	$.post(page,
	   {
		   "upgrade" : "yes",
		   "step"	: "import_users",
	   },
	   function(data)
	   {
		   $("#the_results").append(data);
		},'text');
}


function import_vids()
{
	$("#the_results").html("<strong>Step 3/6 - Importing Videos , Please wait while ClipBucket imports videos....</strong>");
	$.post(page,
	   {
		   "upgrade" : "yes",
		   "step"	: "import_video",
	   },
	   function(data)
	   {
		   $("#the_results").append(data);
		},'text');
}

function import_comments()
{
	$("#the_results").html("<strong>Step 4/6 - Importing Comments , Please wait while ClipBucket imports video comments....</strong>");
	$.post(page,
	   {
		   "upgrade" : "yes",
		   "step"	: "import_comments",
	   },
	   function(data)
	   {
		   $("#the_results").append(data);
		},'text');
}


function import_configs()
{
	$("#the_results").html("<strong>Step 5/6 - Updating Website Configs , Please wait while ClipBucket updates video configurations....</strong>");
	$.post(page,
	   {
		   "upgrade" : "yes",
		   "step"	: "update_configs",
	   },
	   function(data)
	   {
		   $("#the_results").append(data);
		},'text');
}

function final()
{
	$("#the_results").html("<strong>Step 6/6 - Finishing Upgrader...Please wait..</strong>");
	$.post(page,
	   {
		   "upgrade" : "yes",
		   "step"	: "finalize_upgrade",
	   },
	   function(data)
	   {
		   $("#the_results").append(data);
		},'text');
}