<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $image->getId() ?></td>
    </tr>
    <tr>
      <th>Url:</th>
      <td><?php echo $image->getUrl() ?></td>
    </tr>
    <tr>
      <th>Params:</th>
      <td><?php echo $image->getParams() ?></td>
    </tr>
    <tr>
      <th>Hash:</th>
      <td><?php echo $image->getHash() ?></td>
    </tr>
    <tr>
      <th>Path:</th>
      <td><?php echo $image->getPath() ?></td>
    </tr>
    <tr>
      <th>Accessed at:</th>
      <td><?php echo $image->getAccessedAt() ?></td>
    </tr>
    <tr>
      <th>Processed at:</th>
      <td><?php echo $image->getProcessedAt() ?></td>
    </tr>
    <tr>
      <th>Created at:</th>
      <td><?php echo $image->getCreatedAt() ?></td>
    </tr>
    <tr>
      <th>Updated at:</th>
      <td><?php echo $image->getUpdatedAt() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('Backend/edit?id='.$image->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('Backend/index') ?>">List</a>
