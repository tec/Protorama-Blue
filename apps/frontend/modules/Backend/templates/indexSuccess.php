<h1>Images List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Url</th>
      <th>Params</th>
      <th>Hash</th>
      <th>Path</th>
      <th>Accessed at</th>
      <th>Processed at</th>
      <th>Created at</th>
      <th>Updated at</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($images as $image): ?>
    <tr>
      <td><a href="<?php echo url_for('Backend/show?id='.$image->getId()) ?>"><?php echo $image->getId() ?></a></td>
      <td><?php echo $image->getUrl() ?></td>
      <td><?php echo $image->getParams() ?></td>
      <td><?php echo $image->getHash() ?></td>
      <td><?php echo $image->getPath() ?></td>
      <td><?php echo $image->getAccessedAt() ?></td>
      <td><?php echo $image->getProcessedAt() ?></td>
      <td><?php echo $image->getCreatedAt() ?></td>
      <td><?php echo $image->getUpdatedAt() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('Backend/new') ?>">New</a>
