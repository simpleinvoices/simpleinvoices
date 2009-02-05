  <div id="envelope">
  <img id="bol" src="http://www.wiwo.nl/img/logo.png" />
  <div id="heading">  
   <table id="kop">
    <tr>
     <td id="logoblok">
      <ul>
       <li id="wiwo">WiWo Support</li>
       <li class="webadres">http://www.wiwo.nl</li> 
       <li class="webadres">Email: info@wiwo.nl</li> 
      </ul>
     </td>
     <td id="naw">
      <ul id="adres">
       <li>Postbus 1098</li>
       <li>2343 BB  Oegstgeest</li>
      </ul>
      <ul id="phone">
       <li>tel: 071-5237791</li>
       <li>fax: 071-5237791</li>
      </ul>
     </td>
    </tr>
   </table>
  </div>
  </div>
  <ul id="adresblok">
   <li>{naam}</li>
   <li>{tav}</li>
   <li>{adres}</li>
   <li>{postcode} {plaats}</li>
  </ul>
  <table id="faktuurinfo">
   <tr><td class="left">Factuurdatum</td><td>: {fdatum}</td></tr>
   <tr><td class="left">Factuurnummer</td><td>: {fnummer}</td></tr>
   <tr><td class="left">Betaalwijze</td><td>: {betaalwijze}</td></tr>
   <tr><td class="left">{reftext}</td><td>{ref}</td></tr>
  </table>
  <div id="info">
   {info}
  </div>
  <table id="fregels">
   <tr>
    <th class="desc">Artikel</th>
    <th class="aantal">Aantal</th>
    <th class="geld">Stuksprijs</th>
    <th class="geld">BTW</th>
    <th class="geld">Totaal ex BTW</th>
   </tr>
   <tr><td>{faktuurregels}</td></tr>
  </table>
  <div id="totalen">
   <table> 
    <tr><td class="left">Totaal excl. BTW</td>
        <td class="geld">{subtot}</td></tr>
    <tr><td class="left">Verzend/admin. kosten</td>
        <td class="geld">{admin}</td></tr>
    <tr><td class="left">BTW 19%</td>
        <td class="geld">{btwtotaal}</td></tr>
    <tr><td class="left">Totaal te betalen</td>
        <td class="totaal">{totaal}</td></tr>
   </table>
  </div>
  <div id="conditie">
   {betaalconditie}
  </div>
  <table id="footer">
    <tr><td class="left">KvK Leiden, nr. 28064986</td>
        <td class="middle">Postbank 5996861</td>
        <td class="right">BTW nr. NL 1755.85.386.B01</td>
    </tr>
  </table>

