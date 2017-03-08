$(function() {
	if(grp == 1 || grp == 2){
		//loadUrl(host+'beranda', 'Dashboard App');
		$('#judul_kecil').html('Dashboard App');
	}else{
		//loadUrl(host+'backoffice-getmodul/management_file/main', 'Management File');
		$('#judul_kecil').html('Management File');
	}
});	
var grid_nya;
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();
if(dd<10){dd='0'+dd} 
if(mm<10){mm='0'+mm}
today = yyyy+'-'+mm+'-'+dd;

function chart_na(id_selector,type,title,subtitle,title_y,data_x,data_y,satuan){
	switch(type){
	case "column":
	$('#'+id_selector).highcharts({
			chart: {
				type: type
			},
			title: {
				text: title
			},
			subtitle: {
				text: subtitle
			},
			xAxis: {
				type: 'category'
			},
			yAxis: {
				title: {
					text: title_y
				}

			},
			legend: {
				enabled: false
			},
			plotOptions: {
				series: {
					borderWidth: 0,
					dataLabels: {
						enabled: true,
						format: '{point.y:.0f}'
					}
				}
			},

			tooltip: {
				headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
				pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.0f}</b><br/>'
			},

			series: data_x
			
			
			
        });
		break;
		case "line" :
			$('#'+id_selector).highcharts({
				title: {
				text: title,
				x: -20 //center
				},
				subtitle: {
					text: subtitle,
					x: -20
				},
				xAxis: {
					categories: data_y
					//categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
				},
				yAxis: {
					title: {
						text: ''
					},
					plotLines: [{
						value: 0,
						width: 1,
						color: '#808080'
					}]
				},
				tooltip: {
					valueSuffix: ''
				},
				
				series: data_x
				
				/*[{
					name: 'Tokyo',
					data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
				}]*/
			});
		break;
		case "pie":
			 $('#'+id_selector).highcharts({
				chart: {
					plotBackgroundColor: null,
					plotBorderWidth: null,
					plotShadow: false,
					type: 'pie'
				},
				title: {
					text: title
				},
				tooltip: {
					pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
				},
				plotOptions: {
					pie: {
						allowPointSelect: true,
						cursor: 'pointer',
						dataLabels: {
							enabled: true,
							format: '<b>{point.name}</b>: {point.percentage:.1f} %',
							style: {
								color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
							}
						}
					}
				},
				series: data_y
			});
		break;
	}
}
function loadUrl(urls){
	//$("#konten").empty();
    $("#konten").empty().addClass("loading");
   // $("#konten").html("").addClass("loading");
	$.get(urls,function (html){
	    $("#konten").html(html).removeClass("loading");
    });
}

function getClientHeight(){
	var theHeight;
	if (window.innerHeight)
		theHeight=window.innerHeight;
	else if (document.documentElement && document.documentElement.clientHeight) 
		theHeight=document.documentElement.clientHeight;
	else if (document.body) 
		theHeight=document.body.clientHeight;
	
	return theHeight;
}

var divcontainer;
function windowFormPanel(html,judul,width,height){
	divcontainer = $('#jendela');
	$(divcontainer).unbind();
	$('#isiJendela').html(html);
    $(divcontainer).window({
		title:judul,
		width:width,
		height:height,
		autoOpen:false,
		top: Math.round(getClientHeight()/2)-(height/2),
		left: Math.round(getClientWidth()/2)-(width/2),
		modal:true,
		maximizable:false,
		minimizable: false,
		collapsible: false,
		closable: true,
		resizable: false,
	    onBeforeClose:function(){	   
			$(divcontainer).window("close",true);
			//$(divcontainer).window("destroy",true);
			//$(divcontainer).window('refresh');
			return true;
	    }		
    });
    $(divcontainer).window('open');       
}
function windowFormClosePanel(){
    $(divcontainer).window('close');
	//$(divcontainer).window('refresh');
}

var container;
function windowForm(html,judul,width,height){
    container = "win"+Math.floor(Math.random()*9999);
    $("<div id="+container+"></div>").appendTo("body");
    container = "#"+container;
    $(container).html(html);
    $(container).css('padding','5px');
    $(container).window({
       title:judul,
       width:width,
       height:height,
       autoOpen:false,
       maximizable:false,
       minimizable: false,
	   collapsible: false,
       resizable: false,
       closable:true,
       modal:true,
	   onBeforeClose:function(){	   
			$(container).window("close",true);
			$(container).window("destroy",true);
			return true;
	   }
    });
    $(container).window('open');        
}
function closeWindow(){
    $(container).window('close');
    $(container).html("");
}


function getClientWidth(){
	var theWidth;
	if (window.innerWidth) 
		theWidth=window.innerWidth;
	else if (document.documentElement && document.documentElement.clientWidth) 
		theWidth=document.documentElement.clientWidth;
	else if (document.body) 
		theWidth=document.body.clientWidth;

	return theWidth;
}


function genGrid(modnya, divnya, lebarnya, tingginya, par1){
	if(lebarnya == undefined){
		lebarnya = getClientWidth()-250;
	}
	if(tingginya == undefined){
		tingginya = getClientHeight()-300
	}

	var kolom ={};
	var frozen ={};
	var judulnya;
	var param={};
	var urlnya;
	var urlglobal="";
	var url_detil="";
	var post_detil={};
	var fitnya;
	var klik=false;
	var doble_klik=false;
	var pagesizeboy = 10;
	var singleSelek = true;
	var nowrap_nya = true;
	var footer=false;
	var row_number=true;
	var paging=true;
	
	switch(modnya){
		
		case "slider":
			judulnya = "";
			urlnya = "tbl_slider";
			fitnya = true;
			param=par1;
			row_number=true;
			urlglobal = host+'Backoffice-Data/'+urlnya;
			
			kolom[modnya] = [
				{field:'judul',title:'Judul',width:250, halign:'center',align:'left'},				
				{field:'deskripsi',title:'Keterangan',width:350, halign:'center',align:'left'},						
				{field:'gambar',title:'Gambar',width:350, halign:'center',align:'left'},						
				{field:'create_date',title:'Tgl. Buat',width:120, halign:'center',align:'left'},	
				{field:'create_by',title:'Dibuat Oleh',width:150, halign:'center',align:'center'}
			];
		break;
		case "kontent":
			judulnya = "";
			urlnya = "tbl_konten";
			fitnya = true;
			param=par1;
			row_number=true;
			urlglobal = host+'Backoffice-Data/'+urlnya;
			
			kolom[modnya] = [
				{field:'menu',title:'Judul/Menu',width:250, halign:'center',align:'left'},				
				{field:'isi_konten',title:'Isi Konten',width:450, halign:'center',align:'left'},											
				{field:'create_date',title:'Tgl. Buat',width:120, halign:'center',align:'left'},	
				{field:'create_by',title:'Dibuat Oleh',width:150, halign:'center',align:'center'}
			];
		break;
		case "produk":
			judulnya = "";
			urlnya = "tbl_produk";
			fitnya = true;
			param=par1;
			row_number=true;
			urlglobal = host+'Backoffice-Data/'+urlnya;
			
			kolom[modnya] = [
				{field:'flag',title:'Status',width:100, halign:'center',align:'center',
					formatter:function(value,rowData,rowIndex){
						if(value=='F'){return 'Aktif';}
						else{return 'Tidak Aktif';}
					},
					styler:function(value,rowData,rowIndex){
						if(value=='P'){return 'background-color:red;color:#FFFFFF';}
						else return 'background-color:green;color:#FFFFFF';
					}
				},				
				{field:'id',title:'Set Produk Option',width:120, halign:'center',align:'center',
					formatter:function(value,rowData,rowIndex){
						return "<a href='javascript:void(0);' class='btn btn-small' onclick='get_form(\"produk_option\", \"add\", "+value+")'><img src='"+host+"__assets/backend/easyui/themes/icons/cost_object.png'></a>";
					},
				},				
				{field:'judul_produk',title:'Nama Produk',width:250, halign:'center',align:'left'},				
				//{field:'deskripsi_produk',title:'Keterangan',width:350, halign:'center',align:'left'},
				{field:'nama_kategori',title:'Kategori',width:250, halign:'center',align:'left'},					
				{field:'create_date',title:'Tgl. Buat',width:120, halign:'center',align:'left'},	
				{field:'create_by',title:'Dibuat Oleh',width:150, halign:'center',align:'center'}
			];
		break;
		
	}
	
	grid_nya=$("#"+divnya).datagrid({
		title:judulnya,
		height:tingginya,
		width:lebarnya,
		rownumbers:row_number,
		iconCls:'database',
		fit:fitnya,
		striped:true,
		pagination:paging,
		remoteSort: false,
		showFooter:footer,
		singleSelect:singleSelek,
		url: urlglobal,		
		nowrap: nowrap_nya,
		pageSize:pagesizeboy,
		pageList:[10,20,30,40,50,75,100,200],
		queryParams:param,
		frozenColumns:[
			frozen[modnya]
		],
		columns:[
			kolom[modnya]
		],
		onLoadSuccess:function(d){
			$('.btn_grid').linkbutton();
		},
		onClickRow:function(rowIndex,rowData){
			if(modnya=='ldap_user'){
				$('#user_ldap').val(rowData.samaccountname);
				$('#user_na').html(rowData.samaccountname);
				$('#nama_na').html(rowData.displayname);
			}
		},
		onDblClickRow:function(rowIndex,rowData){
			
		},
		toolbar: '#tb_'+modnya,
		rowStyler: function(index,row){
			if(modnya == 'upload_file'){
				if (row.status_data == 'RD'){
					return 'background-color:#FFD1BB;'; // return inline style
				}
			}
			if(modnya == 'request_delete'){
				if (row.status_data == 'RD'){
					return 'background-color:#FFD1BB;'; // return inline style
				}
			}
			
		},
		onLoadSuccess: function(data){
			if(data.total == 0){
				var $panel = $(this).datagrid('getPanel');
				var $info = '<div class="info-empty" style="margin-top:20%;">Data Tidak Tersedia</div>';
				$($panel).find(".datagrid-view").append($info);
			}else{
				$($panel).find(".datagrid-view").append('');
				$('.info-empty').remove();
			}
		},
	});
}


function genform(type, modulnya, submodulnya, stswindow, tabel){
	var urlpost = host+'Backoffice-form/'+submodulnya;
	var urldelete = host+'Backoffice-simpan/'+submodulnya;
	var id_tambahan = "";
	var nama_file = "";
	var table = submodulnya;
	var adafilenya = false;
	
	switch(submodulnya){
		case "slider":
			table = "tbl_slider";
			urldelete = host+'Backoffice-simpan/'+table+'/'+type;
			
		break;
		case "produk":
			table = "tbl_produk";
			urldelete = host+'Backoffice-simpan/'+table+'/'+type;
			
		break;
		case "request_delete":
			table = "tbl_upload_file";
			adafilenya = true;
			urldelete = host+'backoffice-simpan/'+table;
		break;
		
	}
	
	switch(type){
		case "add":
			if(stswindow == undefined){
				$('#grid_nya_'+submodulnya).hide();
				$('#detil_nya_'+submodulnya).empty().show().addClass("loading");
			}
			$.post(urlpost, {'editstatus':'add', 'ts':table, 'id_tambahan':id_tambahan }, function(resp){
				if(stswindow == 'windowform'){
					windowForm(resp, judulwindow, lebar, tinggi);
				}else if(stswindow == 'windowpanel'){
					windowFormPanel(resp, judulwindow, lebar, tinggi);
				}else{
					$('#detil_nya_'+submodulnya).show();
					$('#detil_nya_'+submodulnya).html(resp).removeClass("loading");
				}
			});
		break;
		case "edit":
		case "delete":		
		case "req_delete":		
		case "reject":		
			var row = grid_nya.datagrid('getSelected');
			if(row){
				if(type=='edit'){
					if(stswindow == undefined){
						$('#grid_nya_'+submodulnya).hide();
						$('#detil_nya_'+submodulnya).empty().show().addClass("loading");	
					}
					$.post(urlpost, { 'editstatus':'edit', id:row.id }, function(resp){
						if(stswindow == 'windowform'){
							windowForm(resp, judulwindow, lebar, tinggi);
						}else if(stswindow == 'windowpanel'){
							windowFormPanel(resp, judulwindow, lebar, tinggi);
						}else{
							$('#detil_nya_'+submodulnya).show();
							$('#detil_nya_'+submodulnya).html(resp).removeClass("loading");
						}
					});
				}else if(type=='delete'){
					//if(confirm("Anda Yakin Menghapus Data Ini ?")){
					$.messager.confirm('Back - Office','Anda Yakin Ingin Menghapus Semua Data Ini ?',function(re){
						if(re){
							if(adafilenya){
								nama_file = row.nama_file;
							}
							loadingna();
							$.post(urldelete, {'id':row.id, 'editstatus':'delete'}, function(r){
								if(r==1){
									winLoadingClose();
									$.messager.alert('Back - Office',"Data Terhapus",'info');
									grid_nya.datagrid('reload');								
								}else{
									winLoadingClose();
									console.log(r)
									$.messager.alert('Back - Office',"Gagal Menghapus Data "+r,'error');
								}
							});	
						}
					});	
					//}
				}else if(type=='req_delete'){
					if(row.status_data == 'RD'){
						$.messager.alert('Back - Office',"Data Sudah Direquest Untuk Dihapus",'error');
						return false;
					}
					
					$.messager.confirm('Back - Office','Anda Yakin Untuk Request Hapus Data Ini Kepada Admin ?',function(re){
						if(re){
							loadingna();
							$.post(urldelete, {'id':row.id, 'editstatus':'req_delete'}, function(r){
								if(r==1){
									winLoadingClose();
									$.messager.alert('Back - Office',"Request Hapus Data Terkirim",'info');
									grid_nya.datagrid('reload');								
								}else{
									winLoadingClose();
									console.log(r)
									$.messager.alert('Back - Office',"Gagal Request Hapus Data",'error');
								}
							});	
						}
					});	
				}else if(type=='reject'){
					$.messager.confirm('Back - Office','Anda Yakin Untuk Menolak Request Hapus Data Ini ?',function(re){
						if(re){
							loadingna();
							$.post(urldelete, {'id':row.id, 'editstatus':'reject'}, function(r){
								if(r==1){
									winLoadingClose();
									$.messager.alert('Back - Office',"Berhasil Mengembalikan Data",'info');
									grid_nya.datagrid('reload');								
								}else{
									winLoadingClose();
									console.log(r)
									$.messager.alert('Back - Office',"Gagal Mengembalikan Data",'error');
								}
							});	
						}
					});	
				}
				
			}else{
				$.messager.alert('Back - Office',"Pilih Data Dalam Table/List",'error');
			}
		break;
		
	}
}

function genTab(div, mod, tab_array, height_tab, width_tab){
	/*
	var id_sub_mod=sub_mod.split("_");
	if(typeof(div_panel)!= "undefined" || div_panel!=""){
		
		$(div_panel).panel({
			width:(typeof(width_panel) == "undefined" ? getClientWidth()-268 : width_panel),
			height:(typeof(height_panel) == "undefined" ? getClientHeight()-100 : height_panel),
			title:judul_panel,
			//fit:true,
			tools:[{
					iconCls:'icon-cancel',
					handler:function(){
						$('#grid_nya_'+id_sub_mod[1]).show();
						$('#detil_nya_'+id_sub_mod[1]).hide();
						$('#grid_'+id_sub_mod[1]).datagrid('reload');
					}
			}]
		}); 
		//
	}
	*/
	
	$(div).tabs({
		title:'AA',
		height: getClientHeight()-150,
		width: getClientWidth()-280,
		plain: false,
		fit:true,
		onSelect: function(title,index){
				var isi_tab=title.replace(/ /g,"_");
				var par={};
				console.log(isi_tab);
				$('#'+isi_tab.toLowerCase()).html('').addClass('loading');
				urlnya = host+'backoffice-getmodul/'+mod+'/'+isi_tab.toLowerCase();
				
				switch(mod){
					case "kasir":
						var lantainya = title.split(" ");
						var lantainya = lantainya.length-1;
						
						par['posisi_lantai'] = lantainya;
						urlnya = host+'kasir-lantai/';
					break;
				}
				
				$.post(urlnya,par,function(r){
					$('#'+isi_tab.toLowerCase()).removeClass('loading').html(r);
				});
		},
		onUnselect: function(title,index){
			var isi_tab=title.replace(/ /g,"_");
			$('#'+isi_tab.toLowerCase()).html('');
		},
		selected:0
	});
	
	if(tab_array.length > 0){
		for(var x in tab_array){
			var isi_tab = tab_array[x].replace(/ /g,"_");
			$(div).tabs('add',{
				title:tab_array[x],
				index:x,
				selected:(x == 0 ? true : false),
				content:'<div style="padding: 5px;"><div id="'+isi_tab.toLowerCase()+'" style="height: 200px;">'+isi_tab.toLowerCase()+'zzzz</div></div>'
			});
		}
		var tab = $(div).tabs('select',0);
	}
}

function kumpulAction(type, p1, p2, p3, p4, p5){
	var param = {};
	switch(type){
		case "hapus_gambar":
			$.messager.confirm('Back - Office','Anda Yakin Menghapus Gambar Ini ?',function(re){
			if(re){
				loadingna();
				$.post(host+'Backoffice-HapusGambar', { 'id':p1 }, function(rsp){
					if(rsp==1){
						$('.gbr_'+p1).hide();
						winLoadingClose();
					}else{
						winLoadingClose();
						$.messager.alert('Back - Office',"Gagal Menghapus",'error');
					}
				});
			}
			});
		break;
		case "hapus_logo":
			$.messager.confirm('Back - Office','Anda Yakin Menghapus Gambar Ini ?',function(re){
			if(re){
				loadingna();
				$.post(host+'Backoffice-HapusLogo', { 'param':p1,'value':p2 }, function(rsp){
					if(rsp==1){
						$('.gbr').hide();
						winLoadingClose();
						loadUrl(host+'Backoffice-form/profile');$('#judul_kecil').html('Kontent Website');
					}else{
						winLoadingClose();
						$.messager.alert('Back - Office',"Gagal Menghapus",'error');
					}
				});
			}
			});
		break;
		case "lihatfile":
			$.post(host+'backoffice-getmodul/preview_file/'+p2, { 'nama_file':p1, 'idx':p2 }, function(rsp){
				windowForm(rsp, 'Lihat File Dokumen', 1000, 600);
			});
		break;
		case "lihatfilesharing":
			$.post(host+'backoffice-getmodul/preview_file_sharing/'+p2, { 'nama_file':p1, 'idx':p2, 'untkrj':p3 }, function(rsp){
				windowForm(rsp, 'Lihat File Dokumen', 1000, 600);
			});
		break;
		case "reservation":
			grid = $('#grid_reservasi').datagrid('getSelected');
			$.post(host+'backend/simpan_data/tbl_reservasi_confirm', { 'id':grid.id, 'confirm':p1 }, function(rsp){
				if(rsp == 1){
					$.messager.alert('Back - Office',"Confirm OK",'info');
				}else{
					$.messager.alert('Back - Office',"Failed Confirm",'error');
				}
				$('#grid_reservasi').datagrid('reload');	
			} );
		break;
		case "sharing_file":
			$('#grid_nya_upload_file').hide();
			$('#detil_nya_upload_file').addClass('loading').html('').show();
			$.post(host+'backoffice-getmodul/management_file/sharing_file', { id:p1 }, function(rsp){
				//windowForm(rsp, 'Lihat File Dokumen', 700, 500);
				$('#detil_nya_upload_file').removeClass('loading').html(rsp);
			});
		break;
		case "userrole":
			$.post(host+'backoffice-getmodul/user_management/form_user_role', {'id':p1, 'editstatus':'add'}, function(resp){
				var lebar = getClientWidth()-500;
				var tinggi = getClientHeight()-180;
				windowForm(resp, "User Group Role Privilleges - "+p2, lebar, tinggi);
			});
		break;				
	}
}	

function submit_form(frm,func){
	var url = jQuery('#'+frm).attr("url");
    $.messager.progress();
	jQuery('#'+frm).form('submit',{
            url:url,
            onSubmit: function(){
				var isValid = $(this).form('validate');
				if (!isValid){
					$.messager.progress('close');	// hide progress bar while the form is invalid
				}
				return isValid;	
            },
            success:function(data){
                if (func == undefined ){
                     if (data == "1"){
                        pesan('Data Sudah Disimpan ','Sukses');
                    }else{
                         pesan(data,'Result');
                    }
                }else{
                    func(data);
                }
				$.messager.progress('close');
            },
            error:function(data){
                 if (func == undefined ){
                     pesan(data,'Error');
                }else{
                    func(data);
                }
            }
    });
}

function fillCombo(url, SelID, value, value2, value3, value4){
	//if(Ext.get(SelID).innerHTML == "") return false;
	if (value == undefined) value = "";
	if (value2 == undefined) value2 = "";
	if (value3 == undefined) value3 = "";
	if (value4 == undefined) value4 = "";
	
	$('#'+SelID).empty();
	$.post(url, {"v": value, "v2": value2, "v3": value3, "v4": value4},function(data){
		$('#'+SelID).append(data);
	});

}

function formatDate(date) {
	console.log(date);
	var y = date.getFullYear();
    var m = date.getMonth()+1;
    var d = date.getDate();
    return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
}
function parserDate(s){
	if (!s) return new Date();
    var ss = s.split('-');
    var y = parseInt(ss[0],10);
    var m = parseInt(ss[1],10);
    var d = parseInt(ss[2],10);
    if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
        return new Date(y,m-1,d)
    } else {
        return new Date();
    }
}


function clear_form(id){
	$('#'+id).find("input[type=text], textarea,select").val("");
	//$('.angka').numberbox('setValue',0);
}

var divcontainerz;
function windowLoading(html,judul,width,height){
    divcontainerz = "win"+Math.floor(Math.random()*9999);
    $("<div id="+divcontainerz+"></div>").appendTo("body");
    divcontainerz = "#"+divcontainerz;
    $(divcontainerz).html(html);
    $(divcontainerz).css('padding','5px');
    $(divcontainerz).window({
       title:judul,
       width:width,
       height:height,
       autoOpen:false,
       modal:true,
       maximizable:false,
       resizable:false,
       minimizable:false,
       closable:false,
       collapsible:false,  
    });
    $(divcontainerz).window('open');        
}
function winLoadingClose(){
    $(divcontainerz).window('close');
    //$(divcontainer).html('');
}
function loadingna(){
	windowLoading("<img src='"+host+"__assets/backend/img/loading.gif' style='position: fixed;top: 50%;left: 50%;margin-top: -10px;margin-left: -25px;'/>","Please Wait",200,100);
}

function NumberFormat(value) {
	
    var jml= new String(value);
    if(jml=="null" || jml=="NaN") jml ="0";
    jml1 = jml.split("."); 
    jml2 = jml1[0];
    amount = jml2.split("").reverse();

    var output = "";
    for ( var i = 0; i <= amount.length-1; i++ ){
        output = amount[i] + output;
        if ((i+1) % 3 == 0 && (amount.length-1) !== i)output = '.' + output;
    }
    //if(jml1[1]===undefined) jml1[1] ="00";
   // if(isNaN(output))  output = "0";
    return output; // + "." + jml1[1];
}

function showErrorAlert (reason, detail) {
		var msg='';
		if (reason==='unsupported-file-type') { msg = "Unsupported format " +detail; }
		else {
			console.log("error uploading file", reason, detail);
		}
		$('<div class="alert"> <button type="button" class="close" data-dismiss="alert">&times;</button>'+ 
		 '<strong>File upload error</strong> '+msg+' </div>').prependTo('#alerts');
	}
function konversi_pwd_text(id){
	if($('input#'+id)[0].type=="password")$('input#'+id)[0].type = 'text';
	else $('input#'+id)[0].type = 'password';
}
function gen_editor(id){
	tinymce.init({
		  selector: id,
		  height: 200,
		  plugins: [
				"advlist autolink lists link image charmap print preview anchor",
				"searchreplace visualblocks code fullscreen",
				"insertdatetime media table contextmenu paste jbimages"
		    ],
			
		  // ===========================================
		  // PUT PLUGIN'S BUTTON on the toolbar
		  // ===========================================
		  menubar: true,
		  toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent ",
			
		  // ===========================================
		  // SET RELATIVE_URLS to FALSE (This is required for images to display properly)
		  // ===========================================
			
		  relative_urls: false
		});
		
		tinyMCE.execCommand('mceRemoveControl', true, id);
		tinyMCE.execCommand('mceAddControl', true, id);
	
}
function cariData(divnya, table, acak){
	var post_search = {};
	post_search['kat'] = $('#kat_'+acak).val();
	post_search['key'] = $('#key_'+acak).val();
	post_search['table'] = table;
	
	$('#grid_'+divnya).datagrid('reload', post_search);

	/*
	if($('#kat_'+acak).val()!=''){
		grid_nya.datagrid('reload',post_search);
	}else{
		$.messager.alert('Aldeaz Back-Office',"Pilih Kategori Pencarian",'error');
	}
	//$('#grid_'+typecari).datagrid('reload', post_search);
	*/

}

function advancedSearch(divnya, table, acak, type){
	var post_search = {};
	
	if(type == 'balikin'){
		$('#no_dok_'+acak).val('');
		$('#jns_dok_'+acak).val('');
		$('#tgl_arsip_'+acak).val('');
		$('#perihal_'+acak).val('');
		$('#pengirim_'+acak).val('');
	}else{
		post_search['advanced_search'] = "advanced";
		post_search['no_dokumen'] = $('#no_dok_'+acak).val();
		post_search['jenis_dokumen'] = $('#jns_dok_'+acak).val();
		post_search['tanggal_arsip'] = $('#tgl_arsip_'+acak).val();
		post_search['perihal'] = $('#perihal_'+acak).val();
		post_search['pengirim'] = $('#pengirim_'+acak).val();
		post_search['table'] = table;
	}
	
	$('#grid_'+divnya).datagrid('reload', post_search);
	//$('#grid_'+divnya).datagrid('refreshRow');

	/*
	if($('#kat_'+acak).val()!=''){
		grid_nya.datagrid('reload',post_search);
	}else{
		$.messager.alert('Aldeaz Back-Office',"Pilih Kategori Pencarian",'error');
	}
	//$('#grid_'+typecari).datagrid('reload', post_search);
	*/

}



function simpan_form(id_form,id_cancel,msg){
	if ($('#'+id_form).form('validate')){
		submit_form(id_form,function(r){
			console.log(r)
			if(r==1){
				$.messager.alert('Back-Office',msg,'info');
				$('#'+id_cancel).trigger('click');
				grid_nya.datagrid('reload');
			}else{
				console.log(r);
				$.messager.alert('Back-Office',"Tidak Dapat Menyimpan Data " + r,'error');
			}
		});
	}else{
		$.messager.alert('Homtel Back-Office',"Isi Data Yang Kosong ",'info');
	}
}

function get_form(mod,sts,id,par1,par2){
	param={};
	if(sts=='edit_flag'){param['editstatus']='edit';}else{param['editstatus']=sts;}
	var width_na=800;
	var height_na=350;
	switch(mod){
		case "produk_option":
			$('#grid_nya_produk').hide();
			$('#detil_nya_produk').empty().show().addClass("loading");	
			param['tbl_produk_id']=id;
			$.post(host+'Backoffice-form/'+mod,param,function(r){
				$('#detil_nya_produk').removeClass("loading").html(r);	
			});
			return false;
		break;
		case "planning":
		case "planning_package":
			param['id']=id;
			if(sts!='edit_flag'){
				param['detil_id']=detil_id;
				
				param['total_row']=total_row_plan;
				if(mod=='planning_package')param['header_id']=header_id;
			}
			else param['flag']='F';
		break;
		case "package":
			param['services_id']=par1;
			param['id']=id;
		break;
		case "package_item":
			//param['services_id']=par1;
			param['id_header']=par1;
			param['id']=id;
		break;
		case "reservation":
			//param['services_id']=par1;
			width_na=800;
			height_na=550;
			if(sts=='add'){
				param['id_detil']=par1;
				param['id_trans']=id;
				param['id_pack_header']=par2;
			}else{
				param['id']=id;
			}
		break;
	}
	if(sts=='delete'){
		$.messager.confirm('Back - Office','Are You Sure Delete This Data?',function(re){
			if(re){
				$.post(host+'Backoffice-simpan/'+mod+'/'+sts,param,function(r){
					if(r==1){
						$.messager.alert('Homtel Back-Office',"Data Was Deleted ",'info');
						switch(mod){
							case "planning":get_form_plan('planning',detil_id,total_row_plan,div_id_plan_acak);break;
							case "planning_package":get_form_plan('planning_package',detil_id,total_row_plan,div_id_plan_acak,header_id);break;
							case "package":get_detil('package_detil',par1);break;
							case "package_item":get_form_plan('package',par1,'',div_id_plan_acak);break;
						}
					}else{
						$.messager.alert('Homtel Back-Office',"Failed Deleted ",'error');
					}
				});
			}
		});
		return false;
		
	}
	if(sts=='edit_flag'){
		$.messager.confirm('Homtel Backoffice','Are You Sure ?',function(re){
			if(re){
				$.post(host+'backoffice-simpan/'+mod+'/edit',param,function(r){
					if(r==1){
						$.messager.alert('Homtel Back-Office',"Data Updated ",'info');
						switch(mod){
							case "planning":get_form_plan('planning',detil_id,total_row_plan,div_id_plan_acak);break;
							case "planning_package":get_form_plan('planning_package',detil_id,total_row_plan,div_id_plan_acak,header_id);break;
						}
					}else{
						$.messager.alert('Homtel Back-Office',"Failed Updated ",'error');
					}
				});
			}
		});
		return false;
		
	}
	$.post(host+'backoffice-form/'+mod,param,function(r){
			windowForm(r,'HOMTEL Services',width_na,height_na);
	});
}
function formatDate(date) {
	var bulan=date.getMonth() +1;
	var tgl=date.getDate();
	if(bulan < 10){
		bulan='0'+bulan;
	}
	
	if(tgl < 10){
		tgl='0'+tgl;
	}
	return date.getFullYear() + "-" + bulan + "-" + tgl;
}	
function get_report(mod,acak){
	var param={};
	switch (mod){
		case "report_inv_paid":
		case "report_inv_unpaid":
			param['start_date']=$('#start_date_'+acak).datebox('getValue');
			param['end_date']=$('#end_date_'+acak).datebox('getValue');
			param['type_trans']=$('#type_transaction_'+acak).val();
		break;
	}
	$('#isi_report_'+acak).addClass('loading').html('');
	$.post(host+'Backoffice-Report/'+mod,param,function(r){
		$('#isi_report_'+acak).removeClass('loading').html(r)
	});
	
	
}
function myparser(s){
    if (!s) return new Date();
    var ss = (s.split('-'));
    var y = parseInt(ss[0],10);
    var m = parseInt(ss[1],10);
    var d = parseInt(ss[2],10);
    if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
        return new Date(y,m-1,d);
    } else {
        return new Date();
    }
}
