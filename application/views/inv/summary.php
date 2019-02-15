<table class="table card-table table-vcenter" id="inv-summary">
	<thead>
		<tr>
			<th>
				<?php echo lang('inv_supplier');?>
			</th>
			<th style="min-width: 150px">
				<?php echo lang('inv_amount');?>
			</th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ($supplier_sums as $supplier_id => $sums): ?>
		<tr>
			<td><?php echo $sums[0]->supplier_name; ?></td>
			<td>
				<?php foreach ($sums as $sum):
					echo $sum->amount .' '. $sum->currency .'<br>';
					endforeach;
				?>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>