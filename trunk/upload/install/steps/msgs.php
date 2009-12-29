<?php
		if(count($errors)>0)
		{
			foreach($errors as $error)
			{
				?>
                <span class="error"><?=$error?></span>
                <?
			}
		}
		
		if(count($msgs)>0)
		{
			foreach($msgs as $msg)
			{
				?>
                <span class="msg"><?=$msg?></span>
                <?
			}
		}
?>