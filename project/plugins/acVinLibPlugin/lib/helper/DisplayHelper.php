<?php

function display_field($object, $fieldName) {
    if (is_null($object)) {
        echo '';
        return;
    }
    echo (!is_null($object->$fieldName)) ? $object->$fieldName : '';
}

function get_field($object, $fieldName) {
    if (is_null($object)) {
        return '';
    }
    return (!is_null($object->$fieldName)) ? $object->$fieldName : '';
}

function escape_string_for_latex($string) {
    $disp = str_replace("&#039;", "'", $string);
    $disp = str_replace("&amp;", "&", $disp);
    $disp = str_replace("&", "\&", $disp);
    $disp = str_replace("%", "\%", $disp);
    $disp = str_replace("<", "$<$", $disp);
    $disp = str_replace(">", "$>$", $disp);
    $disp = str_replace("_", "", $disp);

    return $disp;
}

function display_latex_string($string, $sep = '', $limit = null, $nb_max = null) {
    $disp = escape_string_for_latex($string);

    if (!$limit && $sep == '')
        return $disp;

    if ($sep)
        $disp = str_replace($sep, " \\\\ ", $disp);
    if($nb_max!=null && substr_count($disp, " \\\\ ") >= $nb_max) return $disp;

    $len = strlen(strstr($disp," \\\\ "));
    if ($limit!=null && $len > $limit) {
        $d = substr($disp, 0, $limit);
        $pos = strrpos($d, ' ');
        if ($pos !== FALSE) {
            $disp = substr($d, 0, $pos) . "\\\\ " . substr($disp, $pos, $len);
        }
    }
    return $disp;
}

function display_latex_message_communication($string) {
     $longueur_msg = strlen($string);
    $msg = escape_string_for_latex($string);
        if($longueur_msg < 46){
            echo "\centering {~}\\\\
                    \centering {".$msg."}\\\\
                    \centering {~}\\\\
                    \centering {~}";
            return;
        }
        if($longueur_msg < 91){
            echo "\centering {~}\\\\
                    \centering {".$msg."}\\\\
                    \centering {~}";
            return;
        }
        if($longueur_msg < 136){
            echo "\centering {".$msg."}\\\\
                    \centering {~}";
            return;
        }
        echo '\textbf{'.$msg.'} ';
        return;

}

function cut_latex_string($string, $limit) {
    $disp = escape_string_for_latex($string);

    $len = strlen($disp);
    if ($len > $limit) {
        $disp = substr($disp, 0, $limit-3).'...';
    }
    return $disp;
}

function enteteDs($ds,$date_echeance){
    if($ds->getEtablissement()->isNegociant())
        return 'Cet imprimé doit \^{e}tre obligatoirement rempli \underline{\textbf{avant le '.$date_echeance.'}} au plus\\\\tard par tous les négociants, \textbf{détenant des \underline{stocks de vins AOP}}\\\\ \textbf{\underline{ou IGP}} (revendiqués et/ou agrées) \textbf{et quels que soient leurs lieux}\\\\ \textbf{d\'entreposage} selon la liste proposée ci-après conformément à l\'Accord\\\\ Interprofessionnel d\'InterLoire en vigueur.';
    else
        return 'Cet imprimé doit \^{e}tre obligatoirement rempli \textsl{\textbf{avant le '.$date_echeance.'}} au plus tard\\\\par tous les propriétaires, fermiers, métayers, groupements de producteurs\\\\ \textbf{détenant des \textsl{stocks de vins AOP ou IGP}} (revendiqués et/ou agrées)\\\\ \textbf{et quels que soient leurs lieux d\'entreposage} selon la liste proposée ci-après\\\\conformément à l\'Accord Interprofessionnel d\'InterLoire en vigueur.';
}

function formatIban($iban,$sep=' '){
  return preg_replace("/^([A-Z0-9]{4})([0-9]{4})([0-9]{4})([0-9]{4})([0-9]{4})([0-9]{4})([0-9]{3})/","$1$sep$2$sep$3$sep$4$sep$5$sep$6$sep$7",$iban);
}
