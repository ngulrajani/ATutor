<?php global $order; ?>
<table class="data static" summary="" rules="rows">
	<thead>
		<th><?php echo _AT('id'); ?></th>
		<th class="sort"><a href="<?php echo $_SERVER['PHP_SELF']."?col=title".SEP."order=$order"; ?>"><?php echo _AT('title'); ?></a></th>
		<th><?php echo _AT('jb_employer'); ?></th>
		<th><?php echo _AT('categories'); ?></th>	
		<th class="sort"><a href="<?php echo $_SERVER['PHP_SELF']."?col=created_date".SEP."order=$order"; ?>"><?php echo _AT('created_date'); ?></a></th>
		<th class="sort"><a href="<?php echo $_SERVER['PHP_SELF']."?col=closing_date".SEP."order=$order"; ?>"><?php echo _AT('jb_closing_date'); ?></a></th>
		<th></th>
	</thead>
	<tbody>
		<?php 
			if (!empty($this->job_posts)):
				foreach ($this->job_posts as $id=>$row): 
		?>
		<tr>
			<td><a href="<?php echo AT_JB_BASENAME.'view_post.php?jid='.$row['id'];?>" title="<?php echo _AT('jb_view_job_post'); ?>"><?php echo $row['id']; ?></a></td>
			<td><a href="<?php echo AT_JB_BASENAME.'view_post.php?jid='.$row['id'];?>" title="<?php echo $row['title']; ?>"><?php echo $row['title']; ?></a></td>
			<td><?php 
						$employer = new Employer($row['employer_id']);
						echo $employer->getName(); 
					?>
			</td>
			<td>				
				<?php if(is_array($row['categories'])):
						foreach($row['categories'] as $category): 
				?>
				<span><?php echo $this->job_obj->getCategoryNameById($category);?></span> ; 
				<?php endforeach; else: ?>
				<span><?php echo $this->job_obj->getCategoryNameById($row['categories']);?></span>
				<?php endif; ?>
			</td>
			<td><?php echo AT_DATE(_AT("announcement_date_format"), $row['created_date']); ?></td>
			<td><?php echo AT_DATE(_AT("announcement_date_format"), $row['closing_date']); ?></td>
			<td><a href="<?php echo AT_JB_BASENAME . 'employer/edit_post.php?jid='.$row['id']; ?>" title="<?php echo _AT('jb_click_to_edit'); ?>"><?php echo _AT('edit');?></a> | <a href="<?php echo AT_JB_BASENAME . 'employer/view_post.php?action=delete'.SEP.'jid='.$row['id']; ?>" title="<?php echo _AT('jb_click_to_delete'); ?>"><?php echo _AT('delete'); ?></a></td>
		</tr>
		<?php endforeach; endif; ?>
	</tbody>
</table>