<table class="table table-striped table-hover table-bordered" id="example">
<thead>
    <tr>                                             
        <th>
            Reference #
        </th>
        <th>
            Insurance Type
        </th>
        <th>
            Issue Date
        </th>
        <th>
            Expiry Date
        </th>
        <th>
            Options
        </th>

    </tr>
</thead>
<tbody>
    <?php
    $slno = 0;
    foreach ($results as $result){
        $slno++;
        ?>
        <tr>                                          
            <td>
                <?= $result['policy_id'] ?>
            </td>
            <td>
                <?= $result['ins_type'] ?>
            </td>
            <td>
                <?= date('d-m-Y',strtotime($result['created_at'])) ?>
            </td>
            <td>
                <?=((int)$result['expiry_date']==0)?'Not set':date('d-m-Y',strtotime($result['expiry_date'])); ?>
            </td>
            <td>                                                  
                <button data-id="qid" class="btn btn-info btn-sm viewPolicy" value="<?= base64_encode($result['policy_id']) ?>">
                    <span class="glyphicon glyphicon-edit"></span>Renew</button> 
            </td>
        </tr>
    <?php } ?>
</tbody>
</table>
