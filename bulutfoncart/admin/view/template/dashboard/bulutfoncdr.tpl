<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-phone-square"></i> Bulutfon Arama Detayları</h3>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr>
                <td align="center">Arama Tipi</td>
                <td align="center">Yön</td>
                <td align="center">Arayan</td>
                <td align="center">Aranan</td>
                <td align="center">Arama Zamanı</td>
                <td align="center">Cevaplama Zamanı</td>
            </tr>
            </thead>
            <tbody>
                <?php if($cdrs): ?>
                    <?php foreach($cdrs as $cdr) { ?>
                    <tr style="text-align: center">
                        <td><?= $cdr->bf_calltype; ?></td>
                        <td><?= $cdr->direction; ?></td>
                        <td>
                            <?php if(!empty($cdr->customer_link)): ?>
                                <div class="btn-group">
                                    <a href='<?=$cdr->customer_link;?>' class="btn btn-info"><?= $cdr->caller; ?></a>
                                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        <span class="caret"></span>
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="<?=$cdr->customer_link;?>">Müşteri Sayfasına Git</a></li>
                                        <li><a href="tel:+<?= $cdr->caller; ?>">Bu Numarayı Ara</a></li>
                                    </ul>
                                </div>
                            <?php else: ?>
                                <?= $cdr->caller; ?>
                            <?php endif; ?>
                        </td>
                        <td><?= $cdr->callee; ?></td>
                        <td><?= $cdr->call_time; ?></td>
                        <td><?= $cdr->answer_time; ?></td>
                    </tr>
                    <?php } ?>
                <?php else: ?>
                    <tr style="text-align: center">
                        <td colspan="10">Hiç bir arama kaydı yok</td>
                    </tr>
                <?php endif;?>

            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" ><a href="http://projekod.com/eklentiler/bulutfoncart" target="_blank"><img src="http://projekod.com/extAssets/bulutfonCartAssets/getbrand.php" class="img-responsive" /></a> </td>
                    <td colspan="10">
                        <iframe src="http://projekod.com/extAssets/bulutfonCartAssets/getinfo.php" style="width: 100%;border:0;height: 40px;"></iframe>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
