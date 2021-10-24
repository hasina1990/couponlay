<?php require_once("head.php"); ?>
<body class="body">
<div class="main">
	<div class="container">
		<?php require_once("header.php"); ?>
		<div class="section">
			<div class="box-shadow layer first">	
				<div class="data">
					<div class="box attribute">	
						<div class="heading attribute-heading">Location</div>
						<div class="data">
							<a class="link" href="#">New York</a>
							<a class="link" href="#">New York</a>
							<a class="link" href="#">New York</a>
							<a class="link" href="#">New York</a>
							<a class="link" href="#">New York</a>
							<a class="link" href="#">New York</a>
							<a class="link" href="#">New York</a>
							<a class="link" href="#">New York</a>
							<a class="link" href="#">New York</a>
						</div>		
					</div>
					<div class="box attribute">	
						<div class="heading attribute-heading">Property Type</div>
						<div class="data">
							<a class="link first" href="#">Apartment</a>
							<a class="link">Banglows</a>
							<a class="link">Tenament</a>
							<a class="link">Flat</a>
							<a class="link">Villas</a>
							<a class="link">Row House</a>
							<a class="link">Wikend Home</a>
							<a class="link">Fun Club Home</a>
						</div>		
					</div>
					<div class="box attribute">	
						<div class="heading attribute-heading">Price</div>
						<div class="data">
							<a class="link" href="#">500000 - 100000</a>
							<a class="link" href="#">500000 - 100000</a>
							<a class="link" href="#">500000 - 100000</a>
							<a class="link" href="#">500000 - 100000</a>
							<a class="link" href="#">500000 - 100000</a>
							<a class="link" href="#">500000 - 100000</a>
							<a class="link" href="#">500000 - 100000</a>
							<a class="link" href="#">500000 - 100000</a>
						</div>		
					</div>	
				</div>		
			</div>	
			<div class="box content">
				<div class="data">
					<div class="box search-list first">
						<div class="heading search-list-heading">Search</div>
						<div class="data search-list-data">
							<table cellpadding="10" cellspacing="10">
								<tr>
									<td><input type="text" name="search_by[name]" id="search_by-name" value="" class="input-text" placeholder="Property Name, Location etc.." maxlength="254"></td>
									<td>
									<select name="search_by[property_type]" class="input-select">
									<option value="">Property Type</option>
									<optgroup label="Commercial">
									<option value="4">Commercial Office Space</option> 
									<option value="5">Commercial Shop</option> 
									<option value="6">Space in Shopping Mall</option> 
									</optgroup>
									<optgroup label="Residential">
									<option value="1">Multistorey Apartment</option> 
									<option value="2">Builder Floor Apartment</option> 
									<option value="3">Penthouse</option> 
									</optgroup>
									</select>
									</td>
								</tr>
								<tr>
									<td>
									<select name="search_by[price_min]" class="input-select-medium">
									<option value="0" label="Max">Max</option>
									<option value="500000" label="5 Lakhs">5 Lakhs</option>
									<option value="1000000" label="10 Lakhs">10 Lakhs</option>
									<option value="1500000" label="15 Lakhs">15 Lakhs</option>
									<option value="2000000" label="20 Lakhs">20 Lakhs</option>
									<option value="2500000" label="25 Lakhs">25 Lakhs</option>
									<option value="3000000" label="30 Lakhs">30 Lakhs</option>
									<option value="3500000" label="35 Lakhs">35 Lakhs</option>
									<option value="4000000" label="40 Lakhs">40 Lakhs</option>
									<option value="4500000" label="45 Lakhs">45 Lakhs</option>
									<option value="5000000" label="50 Lakhs">50 Lakhs</option>
									<option value="5500000" label="55 Lakhs">55 Lakhs</option>
									<option value="6000000" label="60 Lakhs">60 Lakhs</option>
									<option value="6500000" label="65 Lakhs">65 Lakhs</option>
									<option value="7000000" label="70 Lakhs">70 Lakhs</option>
									<option value="7500000" label="75 Lakhs">75 Lakhs</option>
									<option value="8000000" label="80 Lakhs">80 Lakhs</option>
									<option value="8500000" label="85 Lakhs">85 Lakhs</option>
									<option value="9000000" label="90 Lakhs">90 Lakhs</option>
									<option value="9500000" label="95 Lakhs">95 Lakhs</option>
									<option value="10000000" label="1 Crores">1 Crores</option>
									<option value="12500000" label="1.25 Crores">1.25 Crores</option>
									<option value="15000000" label="1.50 Crores">1.50 Crores</option>
									<option value="20000000" label="2 Crores">2 Crores</option>
									<option value="22500000" label="2.25 Crores">2.25 Crores</option>
									<option value="25000000" label="2.5 Crores">2.5 Crores</option>
									<option value="30000000" label="3 Crores">3 Crores</option>
									<option value="32500000" label="3.25 Crores">3.25 Crores</option>
									<option value="35000000" label="3.5 Crores">3.5 Crores</option>
									<option value="40000000" label="4 Crores">4 Crores</option>
									<option value="42500000" label="4.25 Crores">4.25 Crores</option>
									<option value="45000000" label="4.5 Crores">4.5 Crores</option>
									<option value="50000000" label="5 Crores">5 Crores</option>
									</select>
									&nbsp;To &nbsp;
									<select name="search_by[price_max]" class="input-select-medium">
									<option value="0" label="Max">Max</option>
									<option value="500000" label="5 Lakhs">5 Lakhs</option>
									<option value="1000000" label="10 Lakhs">10 Lakhs</option>
									<option value="1500000" label="15 Lakhs">15 Lakhs</option>
									<option value="2000000" label="20 Lakhs">20 Lakhs</option>
									<option value="2500000" label="25 Lakhs">25 Lakhs</option>
									<option value="3000000" label="30 Lakhs">30 Lakhs</option>
									<option value="3500000" label="35 Lakhs">35 Lakhs</option>
									<option value="4000000" label="40 Lakhs">40 Lakhs</option>
									<option value="4500000" label="45 Lakhs">45 Lakhs</option>
									<option value="5000000" label="50 Lakhs">50 Lakhs</option>
									<option value="5500000" label="55 Lakhs">55 Lakhs</option>
									<option value="6000000" label="60 Lakhs">60 Lakhs</option>
									<option value="6500000" label="65 Lakhs">65 Lakhs</option>
									<option value="7000000" label="70 Lakhs">70 Lakhs</option>
									<option value="7500000" label="75 Lakhs">75 Lakhs</option>
									<option value="8000000" label="80 Lakhs">80 Lakhs</option>
									<option value="8500000" label="85 Lakhs">85 Lakhs</option>
									<option value="9000000" label="90 Lakhs">90 Lakhs</option>
									<option value="9500000" label="95 Lakhs">95 Lakhs</option>
									<option value="10000000" label="1 Crores">1 Crores</option>
									<option value="12500000" label="1.25 Crores">1.25 Crores</option>
									<option value="15000000" label="1.50 Crores">1.50 Crores</option>
									<option value="20000000" label="2 Crores">2 Crores</option>
									<option value="22500000" label="2.25 Crores">2.25 Crores</option>
									<option value="25000000" label="2.5 Crores">2.5 Crores</option>
									<option value="30000000" label="3 Crores">3 Crores</option>
									<option value="32500000" label="3.25 Crores">3.25 Crores</option>
									<option value="35000000" label="3.5 Crores">3.5 Crores</option>
									<option value="40000000" label="4 Crores">4 Crores</option>
									<option value="42500000" label="4.25 Crores">4.25 Crores</option>
									<option value="45000000" label="4.5 Crores">4.5 Crores</option>
									<option value="50000000" label="5 Crores">5 Crores</option>
									</select>
									</td>
									<td>
									<select name="search_by[property_type]" class="input-select">
									<option value="">Property Type</option>
									<optgroup label="Commercial">
									<option value="4">Commercial Office Space</option> 
									<option value="5">Commercial Shop</option> 
									<option value="6">Space in Shopping Mall</option> 
									</optgroup>
									<optgroup label="Residential">
									<option value="1">Multistorey Apartment</option> 
									<option value="2">Builder Floor Apartment</option> 
									<option value="3">Penthouse</option> 
									</optgroup>
									</select>
									</td>
								</tr>
								<tr>
									<td><button type="button" class="input-button">Search</button></td>
									<td></td>
								</tr>
							</table>
						</div>		
					</div>
					
					<div class="box listing first">
						<div class="data list-data">
							<div class="pagination">
								<a class="link" href="#">Previous</a>
								<a class="link" href="#">1</a>
								<a class="link selected" href="#">2</a>
								<a class="link disable" href="#">3</a>
								<a class="link" href="#">4</a>
								<a class="link" href="#">5</a>
								<a class="link" href="#">Next</a>
								<div class="clear"></div>
							</div>
							<div class="box-shadow property first">
								<div class="data">
									<div class="box property-image">
										<div class="data"><img src="images/images.jpg" /></div>		
									</div>
									<div class="box property-data">
										<div class="heading property-data-heading">Uma Apartments</div>		
										<div class="data">
											<span class="item address">Vastrapur, Ahmedabad</span>
											<span class="item property-type">Fully Furnished Aprtment</span>
											<span class="item price">Rs. 4000.00 Per Sq.Ft</span>
											<span class="item desc">A Property has all feature with internal garden and excitment aminities for children and senior citizon.A Property has all feature with internal garden and excitment aminities for children and senior citizon. </span>
										</div>		
									</div>
									<div class="clear"></div>
								</div>		
							</div>
							
							<div class="box-shadow property first">
								<div class="data">
									<div class="box property-image">
										<div class="data"><img src="images/images.jpg" /></div>		
									</div>
									<div class="box property-data">
										<div class="heading property-data-heading">Uma Apartments</div>		
										<div class="data">
											<span class="item address">Vastrapur, Ahmedabad</span>
											<span class="item property-type">Fully Furnished Aprtment</span>
											<span class="item price">Rs. 4000.00 Per Sq.Ft</span>
											<span class="item desc">A Property has all feature with internal garden and excitment aminities for children and senior citizon.A Property has all feature with internal garden and excitment aminities for children and senior citizon. </span>
										</div>		
									</div>
									<div class="clear"></div>
								</div>		
							</div>
							
							<div class="box-shadow property first">
								<div class="data">
									<div class="box property-image">
										<div class="data"><img src="images/images.jpg" /></div>		
									</div>
									<div class="box property-data">
										<div class="heading property-data-heading">Uma Apartments</div>		
										<div class="data">
											<span class="item address">Vastrapur, Ahmedabad</span>
											<span class="item property-type">Fully Furnished Aprtment</span>
											<span class="item price">Rs. 4000.00 Per Sq.Ft</span>
											<span class="item desc">A Property has all feature with internal garden and excitment aminities for children and senior citizon.A Property has all feature with internal garden and excitment aminities for children and senior citizon. </span>
										</div>		
									</div>
									<div class="clear"></div>
								</div>		
							</div>
							
							<div class="box-shadow property first">
								<div class="data">
									<div class="box property-image">
										<div class="data"><img src="images/images.jpg" /></div>		
									</div>
									<div class="box property-data">
										<div class="heading property-data-heading">Uma Apartments</div>		
										<div class="data">
											<span class="item address">Vastrapur, Ahmedabad</span>
											<span class="item property-type">Fully Furnished Aprtment</span>
											<span class="item price">Rs. 4000.00 Per Sq.Ft</span>
											<span class="item desc">A Property has all feature with internal garden and excitment aminities for children and senior citizon.A Property has all feature with internal garden and excitment aminities for children and senior citizon. </span>
										</div>		
									</div>
									<div class="clear"></div>
								</div>		
							</div>
							
							<div class="box-shadow property first">
								<div class="data">
									<div class="box property-image">
										<div class="data"><img src="images/images.jpg" /></div>		
									</div>
									<div class="box property-data">
										<div class="heading property-data-heading">Uma Apartments</div>		
										<div class="data">
											<span class="item address">Vastrapur, Ahmedabad</span>
											<span class="item property-type">Fully Furnished Aprtment</span>
											<span class="item price">Rs. 4000.00 Per Sq.Ft</span>
											<span class="item desc">A Property has all feature with internal garden and excitment aminities for children and senior citizon.A Property has all feature with internal garden and excitment aminities for children and senior citizon. </span>
										</div>		
									</div>
									<div class="clear"></div>
								</div>		
							</div>
						</div>
					</div>
					
				</div>		
			</div>	
			<div class="clear"></div>
		</div>
		<?php require_once("footer.php"); ?>
	</div>
</div>		
</body>
</html>

		