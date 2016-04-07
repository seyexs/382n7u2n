<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 */
 echo json_encode(array('a','b'));
?>
<div id="inp-form-box" class="box g16 inp-row1">
                <div class="header">Sample form <span class="box-desc">with validation</span></div>
                <div class="content gcont no-pad-btm">
                    <?php
						$form = $this->beginWidget('ext.CAxActiveForm', array(
							'id' => 'test-form',
							'enableAjaxValidation' => false,
							'htmlOptions' => array(
								//'enctype' => 'multipart/form-data'
							)
						));
					?>
					<table class="tablesorter">
						<thead class="header">
							<tr>
								<th rowspan="2">Nama Koperasi</th>
								<th rowspan="2">Alamat</th>
								<th rowspan="2">Jenis</th>
								<th rowspan="2">Anggota</th>
								<th colspan="3">Mutasi Transaksi</th>
							</tr>
							<tr>
								<th>Anggota</th>
								<th>Setor + KAS</th>
								<th>PPOB</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Citra Abadi</td>
								<td>247 Independence St, MT 51382</td>
								<td>
									KSP
								</td>
								<td>754</td>
								<td><div class="inp-cont no-pad"><input type="text" class="g5"/></div></td>
								<td><div class="inp-cont no-pad"><input type="text" class="g8"/></div></td>
								<td><div class="inp-cont no-pad"><input type="text" class="g8"/></div></td>
							</tr>
							<tr>
								<td>KUD</td>
								<td>463 Coney Island Ave, NY 11230</td>
								<td>
									KSU
								</td>
								<td>412</td>
								<td><div class="inp-cont no-pad"><input type="text" class="g5"/></div></td>
								<td><div class="inp-cont no-pad"><input type="text" class="g8"/></div></td>
								<td><div class="inp-cont no-pad"><input type="text" class="g8"/></div></td>
							</tr>
							<tr>
								<td><div class="inp-cont no-pad"><input type="text" class="g5"/></div></td>
								<td><div class="inp-cont no-pad"><input type="text" class="g5"/></div></td>
								<td>
									<div class="inp-cont no-pad">
										<select class="required inset">
											<option>KSU</option>
											<option>KSP</option>
										</select>
									</div>
								</td>
								<td><div class="inp-cont no-pad"><input type="text" class="g5"/></div></td>
								<td><div class="inp-cont no-pad"><input type="text" class="g5"/></div></td>
								<td><div class="inp-cont no-pad"><input type="text" class="g8"/></div></td>
								<td><div class="inp-cont no-pad"><input type="text" class="g8"/></div></td>
							</tr>
						</tbody>
					</table>
                        
                        
                        <button type="submit" class="green flt-r g3">Submit</button>
                    <?php $this->endWidget(); ?>
                </div>
            </div>
			