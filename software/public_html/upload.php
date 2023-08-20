<?php  

error_reporting(E_ALL);
ini_set('display_errors', 1);	


include 'include/conn.php';

 

 $messagelist="";
 
 
 $total_articles="SELECT * FROM `articles_scopus`,products WHERE articles_scopus.product_id=products.productid and camp_id=$CampID ";
           $total_articles=$conn->prepare($total_articles);
           $total_articles->execute();
           if($total_articles->rowCount()>0){
               $articles_list=$total_articles->fetchAll();
           
           foreach ($articles_list as $article) {

                               $disp = htmlentities(addslashes($article['disp']));
                               $disp='%'.$disp.'%';
                               $journalname = htmlentities(addslashes($article['product_name'])); 
                               $journalname1 = '%'.$journalname.'%';
                               $url = htmlentities(addslashes($article['url']));
                               $volume = htmlentities(addslashes($article['volume']));
                               $journalCover = htmlentities(addslashes($article['product_cover']));
                               $coverurl = htmlentities(addslashes($article['product_cover_url']));
                               $issue = htmlentities(addslashes($article['issue']));
                               $year = htmlentities(addslashes($article['year']));
                               $article_title = htmlentities(addslashes($article['title']));
                               $author = htmlentities(addslashes($article['authors']));
                               $abstract = htmlentities(addslashes($article['abstract']));
                               $absurl = htmlentities(addslashes($article['absurl']));
                               $doi = htmlentities(addslashes($article['doi']));
                               $absurldownload = htmlentities(addslashes($article['absurl_download']));
                               $article_id = htmlentities(addslashes($article['article_id']));
                             
                               $product_cover=$article['product_cover'];
                               $product_name=$article['product_name'];

			$messagelist = $messagelist . " 
									
									<tr valign='top'>
										<td height='145'>
										</br>	<table width='793' border='0' style='border: 2px solid #b4d1e3; padding-left: 6px;'>
												<tr>
													<td width='677'><span style='font-size:16px; font-family:Georgia, Times, serif; color:#000; '><strong>" . $journalname . "</strong></span></td>
													<td width='108' rowspan='4' align='center' valign='middle'><img src='https://mailshub.net/product_cover/" . $product_cover . "' style='width: 100px; padding: 11px 7px 0 10px;' /></td>
												</tr>
												<tr>
													<td><span style='font-family: Georgia,Times,serif';>Volume " . $volume . ", ";

                                                    if ($issue == '') {
                                                        $messagelist = $messagelist . "";
                                                    } elseif ($issue) {
                                                        $messagelist = $messagelist . "Issue " . $issue . ", ";
                                                    }

			$messagelist = $messagelist . " " . $year . "</span></td>
												</tr>
												<tr>
													<td>
													<span><strong style='font-family: Georgia,Times,serif';>Title: </strong><br/>
													<span style='font-size:12px; font-family:Georgia; color:#1a1a1cc7;'><b style='font-family: Georgia,Times,serif';>" . $article_title . "</b></span></td>
												</tr>
												<tr>
													<td> <span><strong style='font-family: Georgia,Times,serif';>Author: </strong><br/>
													<strong style='font-size:12px; color:#1a1a1cc7; font-family: Georgia,Times,serif';>" . $author . "</strong></span></td>
												</tr>
												<tr>
								                    <td> <span><strong style='font-family: Georgia,Times,serif';>DOI:</strong>
														<br/> 
                                                    <a style='font-size:12px;  font-family: Georgia,Times,serif'; href='http://dx.doi.org/" . $doi . "' target='_blank' style='
                                                     color: #15c;'> " . $doi . "</a> <br/>";
       
                     $messagelist = $messagelist . "</td>
												</tr>
											</table>
										</td>
									</tr>";
                              


           } 
         } 
