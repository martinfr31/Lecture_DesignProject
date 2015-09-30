<%@ Page Language="C#" AutoEventWireup="true" CodeBehind="createFile.aspx.cs" Inherits="ProSeminarTest.WebForm1" %>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
</head>
<body>

    <form id="form1" runat="server">

    <script language="javascript" type="text/javascript">

      function getParameterByName(name) {
            name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
            var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
                results = regex.exec(location.search);
            return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
      }

        var parm = getParameterByName('created');
        if (parm == 0) {
            <%  createFile(); %>
            var key = getParameterByName('Key');
            //window.location = "./index.html?Key=" + key + "&created=1";
        }
    </script>

    </form>
</body>
</html>
