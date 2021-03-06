function(doc) {
    if (doc.type != "DRM") {

        return;
    }
    var transmission = "NON";
    var horodatage = null;
    var coherente = null;
    var diff = null;
    if (doc.transmission_douane) {
    	if (doc.transmission_douane.success) {
    		transmission = "SUCCESS";
    	  horodatage = doc.transmission_douane.horodatage;
    	}else{
    		transmission = "ERREUR";
    	}
    	coherente = doc.transmission_douane.coherente;
    	diff = doc.transmission_douane.diff;
    }
    var type_creation = null;
    if(doc.type_creation){
      type_creation = doc.type_creation;
    }else{
      type_creation = "NON_RENSEIGNE";
    }
    emit([doc.identifiant, doc.campagne, doc.periode, doc.version, doc.mode_de_saisie, doc.valide.date_saisie, doc.douane.envoi, doc.douane.accuse, doc.numero_archive, doc.teledeclare, transmission, horodatage, coherente, diff, type_creation], 1);
}
