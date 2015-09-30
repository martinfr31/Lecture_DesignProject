using System;
using System.Web;

namespace ProSeminarTest
{
    public class MyModule1 : IHttpModule
    {
        /// <summary>
        /// Sie müssen dieses Modul in der Datei "Web.config" Ihres
        /// Webs konfigurieren und bei IIS registrieren, bevor Sie ihn verwenden können. Weitere Informationen
        /// finden Sie unter folgendem Link: http://go.microsoft.com/?linkid=8101007
        /// </summary>
        #region IHttpModule Members

        public void Dispose()
        {
            //Bereinigungscode hier.
        }

        public void Init(HttpApplication context)
        {
            // Unten finden Sie ein Beispiel, wie das Ereignis "LogRequest" verarbeitet und 
            // benutzerdefinierte Protokollierungsimplementierung bereitgestellt werden kann
            context.LogRequest += new EventHandler(OnLogRequest);
        }

        #endregion

        public void OnLogRequest(Object source, EventArgs e)
        {
            //benutzerdefinierte Protokollierungslogik kann hier eingefügt werden
        }
    }
}
