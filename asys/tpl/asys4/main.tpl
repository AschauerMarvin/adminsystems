<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">        
  <head>              
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />              
    <link rel="shortcut icon" href="../tpl/{used_tpl}/favicon.png" type="image/x-icon" />              
    <meta name="generator" content="Landsknecht Adminsystems {asys_version}" />
    <link rel="stylesheet" href="../tpl/{used_tpl}/style.css" type="text/css" media="all" />              
{load_javascript}               
    <title>{sitetitle}</title>    
  </head> 
<body>
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<div id="wrapper">
  <div id="preface">
        <div id="left">
				Adminsystems Version {asys_version} | Core {asys_core_version}_{db_type} | Mem {asys_memory} KB | Hi, {asys_u_user} {mod_info}
        </div>	
    </div>
    <div id="header">
            <div id="logo">
              <a href="index.php"><img src="{logo_asys}" alt="Adminsystems" /></a>    			      
            </div>
            <div id="middle">
            <h2>{sitetitle}</h2>
            </div>
    </div>
	
         <div id="navigation" class="menu">
          <ul>
            <!-- START BLOCK : navbar --> 
            <li><a href="{href}" rel="{rel}" {overlib}>{navbar_name}</a>
            <!-- START BLOCK : subnav -->
            <ul>
            <!-- START BLOCK : subnavelement -->
            <li><a href="{href}" rel="{rel}" {overlib}>{navbar_name}</a></li>
            <!-- END BLOCK : subnavelement --> 
            </ul>
            <!-- END BLOCK : subnav --> 
            </li>      
            <!-- END BLOCK : navbar -->        
          </ul> 
                                          
         </div>               

	
    <div id="main_content">
		<div id="main_content_header"><h2>{page_title}</h2></div>
			<div id="main_content_inner">
			<br />
     <!-- START BLOCK : errormsg -->
      <div class="{err_or_inf}">
      {err_msg}
      </div>  
      <!-- END BLOCK : errormsg -->
      <!-- START BLOCK : information --> 
      <div class="{type}">
      {err_msg}
      </div> 
      <!-- END BLOCK : information -->     
        <!-- START BLOCK : nobrcontent -->
        {content}
        <!-- END BLOCK : nobrcontent -->       
        <!-- START BLOCK : content -->            
        {content}
        <br /><br />       
        <!-- END BLOCK : content -->
        <!-- INCLUDE BLOCK : formarea -->       
        <!-- INCLUDE BLOCK : table --> 
        <!-- INCLUDE BLOCK : choose_menu -->                       
        <!-- INCLUDE BLOCK : site_about -->                    
        <!-- INCLUDE BLOCK : custom -->  
     <!-- START BLOCK : debug_element -->
      <br />
	  <h3>Debug Element {debug_id}</h3>      
	  <hr /> 
      <pre>
      {debug_element} 
      </pre>
      <hr />
      <br />
      <!-- END BLOCK : debug_element -->     
			</div>
    </div>
        <div style="clear: both;">&nbsp;</div>			
	
</div>


</body>
</html>