	<div class="row">
	<div class="span6 col-md-6">
					<div class="dataTables_info" id="editable-sample_info">
						<span><b>Total Records:</b><span class="badge badge-important"><?php echo $this->Paginator->counter('{{count}}'); ?></span></span>
						<span><b>Page of pages:</b><span class="badge badge-important"><?php echo $this->Paginator->counter(); ?></span></span>
					</div>
	</div>
	
	
	<div class="span6 col-md-6">
		<div class="dataTables_paginate paging_bootstrap">
			<ul class="pagination">
				<?php
				echo ($this->Paginator->hasPrev()) ? $this->Paginator->prev('<<', array('tag' => 'li'), null, null) : '<li class="disabled"><a href="javascript:;">Previous</a></li>';
				echo $this->Paginator->numbers(array('separator' => false, 'tag' => 'li'));
				echo ($this->Paginator->hasNext()) ? $this->Paginator->next('>>', array('tag' => 'li'), null, null) : '<li class="disabled"><a href="javascript:;">Next</a></li>';
				?>
			</ul>
		</div>
	</div>
	</div>