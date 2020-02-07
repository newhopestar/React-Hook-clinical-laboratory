import React, { useState, useEffect } from "react";

export default function OrderDetails(props) {
  const [isLoading, setIsLoading] = useState(false);
  const [isError, setIsError] = useState(false);
  const [data, setData] = useState([]);
  var order=props.order;
  var userData = JSON.parse( localStorage.getItem('auth') );

  useEffect(() => {
    const fetchData = async() => {
      setIsError(false);
      setIsLoading(true);
      
      var url = process.env.REACT_APP_API + '/api.php?l=' + order + '&m=Details';
      try {
        let response = await fetch(url, {
          method: 'get',
          headers:{
            'Authorization': `Bearer ${userData.access_token}`,
         }
         })
        let responseJson = await response.json();
         setData(responseJson);
      } catch (error) {
        console.log("error-----")
        setIsError(true);
      }
      setIsLoading(false);
    };
    
    if (typeof order == "string" && order.length > 1){
      fetchData();
    }
    else {
    }
  }, [props.order, order, userData.access_token]);

  const showPDF = (ID,PDF) => {
    
    //var blob = new Blob([PDF], {type:"application/pdf"});
    var blob = b64toBlob(PDF, 'application/pdf');
    var filename = ID + ".pdf";
    
    if (typeof window.navigator.msSaveBlob !== "undefined") {
        // IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed.
        console.log("using msSaveBlob");
        window.navigator.msSaveBlob(blob, filename);
    } else {
        var URL = window.URL || window.webkitURL;
        var downloadUrl = URL.createObjectURL(blob);

        if (filename) {
            // Use HTML5 a[download] attribute to specify filename.
            var a = document.createElement("a");
            
            // Safari doesn"t support this yet.
            if (typeof a.download === "undefined") {
                console.log("using window location");
                //window.location = downloadUrl;
            } else {
                console.log("using a click click");
                a.href = downloadUrl;
                a.target = "_blank";
                a.download = filename;
                document.body.appendChild(a);
                a.click();
            }
        } else {
            console.log("using window download last resort");
            window.location = downloadUrl;
        }

        setTimeout(function () {
            URL.revokeObjectURL(downloadUrl);
        }, 100); // Cleanup
    }    		
  }

  const b64toBlob = (b64Data, contentType='', sliceSize=512) => {
    const byteCharacters = atob(b64Data);
    const byteArrays = [];
  
    for (let offset = 0; offset < byteCharacters.length; offset += sliceSize) {
      const slice = byteCharacters.slice(offset, offset + sliceSize);
  
      const byteNumbers = new Array(slice.length);
      for (let i = 0; i < slice.length; i++) {
        byteNumbers[i] = slice.charCodeAt(i);
      }
  
      const byteArray = new Uint8Array(byteNumbers);
      byteArrays.push(byteArray);
    }
  
    const blob = new Blob(byteArrays, {type: contentType});
    return blob;
  }


  function getImage(status) {
    const statusOptions = ["Created", "Ignored", "Replaced", "Completed"];
    
    if(statusOptions.indexOf(status) !== -1) {
      return "./images/test-" + status.toLowerCase() + ".svg";
    } else {
      return "./images/test-unknown.svg";
    }
  }
  

  return (
    <div className="fixedElement">
      <div>
        {isError && <div>Something went wrong ...</div>}
        
        {isLoading ? (
          <div className="lander">Loading ...</div>
        ) : (
        <table className="table table-striped table-hover order-details">
          <thead className="thead-dark">
            <tr>
              <td>Status</td>
              <td>Procedure</td>
              <td>Description</td>
              <td>Priority</td>
              <td>View</td>
            </tr>
          </thead>
          
          <tbody>
            {data && data.map((item, index) => (
            <tr key={`test-code-key${index}`}>
              <td><img className="pdf" 
                       src={ getImage(item.Status) } alt={item.Status} title={item.Status} /></td>
              <td>{item.Procedure}</td>
              <td>{item.ProcedureDesc}</td>
              <td>{item.Priority}</td>
              <td>{item.PDF ? (
                <button className="download" onClick = {()=>showPDF(item.PDFName + "-" + index,item.PDF)}
                ><img className="pdf" src="./images/adobe-pdf-icon.svg" alt="Download" /></button>                
              ) : ( "" )}
              </td>              
            </tr>              
            ))}
          </tbody>
        </table>
        )}
      </div>
    </div>
  );
}