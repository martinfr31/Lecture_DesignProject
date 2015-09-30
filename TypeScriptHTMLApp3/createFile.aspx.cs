using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.IO;

namespace ProSeminarTest
{
    public partial class WebForm1 : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
   
        }
        public void createFile()
        {

            HttpCookie aCookie = Request.Cookies["Chart1data"];
            //// Read the cookie information and display it.
            if (aCookie != null)
            {
                string name = Request["name"];

                using (StreamWriter _testData = new StreamWriter(Server.MapPath("~/" + name), true))
                {
                    
                    _testData.WriteLine(decode(aCookie.Value.ToString()));
                }
            }
            else
            {
              // Response.Write("not found");
            } 
        }
        public string decode(string y)
        {
            
            while ((y.IndexOf("+") > -1) || (y.IndexOf("$") > -1) || (y.IndexOf("(") > -1) || (y.IndexOf(")") > -1) || (y.IndexOf("?") > -1) || (y.IndexOf("&") > -1))
            {
                y = y.Replace("+", "[");
                y = y.Replace("$", "]");
                y = y.Replace("(", "{");
                y = y.Replace(")", "}");
                y = y.Replace("?", " ");
                y = y.Replace("&", @"""");
            }
            return y;
        }
    }
}