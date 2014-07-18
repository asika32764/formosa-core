<?php
/**
 * Part of formosa project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

/** @var $exception Exception */
$exception = $data->exception;
?>
<style>
	body {
		margin: 0;
		padding: 30px;
		font-family: Helvetica, Arial, Verdana, sans-serif;
		font-size: 15px;
		line-height: 150%;
	}

	h1 {
		margin      : 0;
		font-size   : 48px;
		font-weight : normal;
		line-height : 48px;
		border-bottom: 1px solid #eee;
	}

	strong {
		display : inline-block;
		width   : 85px;
	}

	table {
		border-spacing: 0;
		border-collapse: collapse;
		width: 100%;
	}

	table tbody tr td {
		padding: 8px;
		line-height: 1.42857143;
		vertical-align: top;
		border-top: 1px solid #ddd;
		font-family: monospace;
	}

	table>tbody>tr:nth-child(odd)>td {
		background-color: #f9f9f9;
	}
</style>

<h1>Formosa Error</h1>

<p>Application shut down because of following error:</p>

<h2>Error Details</h2>

<div>
	<strong>Type:</strong> <?php echo get_class($exception); ?>
</div>
<div>
	<strong>Message:</strong> <?php echo $exception->getMessage(); ?>
</div>
<div>
	<strong>File:</strong> <?php echo $exception->getFile(); ?>
</div>
<div>
	<strong>Line:</strong> <?php echo $exception->getLine(); ?>
</div>

<h2>BavkTrace</h2>

<table>
	<?php foreach ($exception->getTrace() as $i => $trace): ?>
	<tr>
		<td>
			#<?php echo $i ; ?>
		</td>
		<td>
			<?php echo $trace['file']; ?> (<?php echo $trace['line']; ?>)
		</td>
		<td>
			<?php echo $trace['class']; ?>::<?php echo $trace['function']; ?>()
		</td>
	</tr>
	<?php endforeach; ?>
</table>
