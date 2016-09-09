function afficheImage(source) {
    window.open(source, 'pop', 'status=no,directories=no,toolbar=no,location=no,menubar=no,scrollbars=yes,resizable=yes');
}

function viderFormulaire(criteres) {
    criteres = criteres.split(";");
    var champReinit = "";
    var valeurChamp = "";
    for (var i = 0; i < (criteres.length); i++) {
        champReinit = eval("document.forms['recherche_avancee']." + criteres[i].substring(0, criteres[i].indexOf("=")));
        valeurChamp = criteres[i].substring(criteres[i].indexOf("=") + 1);
        if (champReinit) {
            var sType = champReinit.type;
            if (!sType) {
                for (var i = 0; i < champReinit.length; i++)
                    champReinit[i].checked = false;
            } else if (sType == 'checkbox')
                champReinit.checked = false;
            else
                champReinit.value = valeurChamp;
        }
    }
}

function switchId(ancienIdItem, nouvelIdItem) {
    var itemSwitch = window.document.getElementById(ancienIdItem);
    if (itemSwitch != null) {
        itemSwitch.id = nouvelIdItem;
    }
};
var field1 = "";
var field2 = "";
var fieldRequete = "";
var texte = "";
var nomApplet = "";
var nomForm = "";
var pageTete = false;
var ajoutAuto = "",
    modifAuto = "";
var typeInsertion = "";

function ouvrirPhoto(urlPhoto, largeur, hauteur) {
    x = (screen.availWidth - largeur) / 2;
    y = (screen.availHeight - 30 - hauteur) / 2;
    attrs = "height=" + hauteur + ", width=" + largeur + ", left=" + x + ", top=" + y;
    if (y < 0) {
        attrs += ",scrollbars=yes"
    }
    fenetre = window.open(urlPhoto, 'photo', attrs);
}

function ouvrirFenetrePlan(url, nom) {
    window.open(url, nom, "width=520,height=500,scrollbars=yes, status=yes, resizable=1");
}

function showPhototheque(action) {
    window.open('/servlet/com.jsbsoft.jtf.core.SG?PROC=SAISIE_MEDIA&ACTION=' + action, 'photo', 'width=650,height=535,top=20,left=100,scrollbars=yes, status=yes,resizable=yes');
}

function showMessageField(typeAide, f1, f2) {
    showMessageField2(typeAide, f1, f2, '');
}

function showMessageField2(typeAide, f1, f2, form) {
    nomForm = form;
    field1 = f1;
    field2 = f2;
    fieldRequete = '';
    texte = '';
    nomApplet = '';
    typeInsertion = '';
    var oForm;
    if (inBackOffice()) {
        oForm = document.forms[0];
    } else {
        if (form.length > 0)
            oForm = document.forms[form];
        if (!oForm) {
            oForm = (document.forms['form_saisie_front']) ? document.forms['form_saisie_front'] : document.forms['recherche_avancee'];
            nomForm = oForm.id;
        }
    }
    var value = oForm.elements[f1].value;
    if (!value)
        value = "";
    if (typeAide.indexOf('structure') != -1) {
        var indexSlash1 = typeAide.indexOf('/');
        var lg = '';
        var filtre = '';
        if (indexSlash1 != -1) {
            var indexSlash2 = typeAide.indexOf('/', indexSlash1 + 1);
            if (indexSlash2 != -1) {
                lg = typeAide.substring(indexSlash1 + 1, indexSlash2);
                filtre = typeAide.substring(indexSlash2 + 1);
            } else {
                lg = typeAide.substring(indexSlash1 + 1);
            }
        }
        sList = window.open('/adminsite/menu/menu.jsp?MODE=STRUCTURE&CODE=' + value + '&LANGUE=' + lg + '&FILTRE=' + filtre, 'menu2', 'scrollbars=yes, resizable=yes, status=yes, width=600, height=400, top=320, left=320');
    } else if (typeAide.indexOf('strbo/') != -1) {
        var indexSlash1 = typeAide.indexOf('/');
        var indexSlash2 = typeAide.indexOf('/', indexSlash1 + 1);
        var indexSlash3 = typeAide.indexOf('/', indexSlash2 + 1);
        var indexSlash4 = typeAide.indexOf('/', indexSlash3 + 1);
        var indexSlash5 = typeAide.indexOf('/', indexSlash4 + 1);
        var permission = typeAide.substring(indexSlash1 + 1, indexSlash4);
        var lg = typeAide.substring(indexSlash4 + 1, indexSlash5);
        var filtre = typeAide.substring(indexSlash5 + 1, typeAide.length);
        sList = window.open('/adminsite/menu/menu.jsp?MODE=STRUCTURE&CODE=' + value + '&PERMISSION=' + permission + '&LANGUE=' + lg + '&FILTRE=' + filtre, 'menu2', 'scrollbars=yes, resizable=yes, status=yes, width=600, height=400, top=320, left=320');
    } else if (typeAide.indexOf('strfo') != -1) {
        var elem = typeAide.split('/');
        lg = '';
        filtre = '';
        racine = '';
        if (elem[1])
            lg = elem[1];
        if (elem[2])
            filtre = elem[2];
        if (elem[3])
            racine = elem[3];
        sList = window.open('/adminsite/menu/menu.jsp?MODE=STRUCTURE&CODE=' + value + '&LANGUE=' + lg + '&FILTRE=' + filtre + '&FRONT=true&RACINE=' + racine, 'menu2', 'scrollbars=yes, resizable=yes, status=yes, width=600, height=400, top=320, left=320');
    } else if (typeAide == 'rubrique' || typeAide.indexOf('rubrique') != -1) {
        var lg = '';
        var racine = '';
        if (typeAide != 'rubrique') {
            var indexSlash = typeAide.indexOf('/');
            if (indexSlash != -1) {
                racine = typeAide.substring(indexSlash + 1, typeAide.length);
                lg = typeAide.substring(typeAide.indexOf('rubrique') + 8, indexSlash);
            } else {
                lg = typeAide.substring(typeAide.indexOf('rubrique') + 8, typeAide.length);
            }
        }
        sList = window.open('/adminsite/menu/menu.jsp?MODE=RUBRIQUE&CODE=' + value + '&LANGUE=' + lg + '&RACINE=' + racine, 'menu2', 'scrollbars=yes, resizable=yes, status=yes, width=600, height=400, top=320, left=320');
    } else if (typeAide.indexOf('rubbo/') != -1) {
        var indexSlash1 = typeAide.indexOf('/');
        var indexSlash2 = typeAide.indexOf('/', indexSlash1 + 1);
        var indexSlash3 = typeAide.indexOf('/', indexSlash2 + 1);
        var indexSlash4 = typeAide.indexOf('/', indexSlash3 + 1);
        var permission = '';
        var racine = '';
        if (indexSlash3 != -1) {
            permission = typeAide.substring(indexSlash1 + 1, indexSlash4);
            racine = typeAide.substring(indexSlash4 + 1, typeAide.length);
        } else if (indexSlash2 != -1) {
            permission = typeAide.substring(indexSlash1 + 1, indexSlash2);
            racine = typeAide.substring(indexSlash2 + 1, typeAide.length);
        } else {
            permission = typeAide.substring(indexSlash1 + 1, typeAide.length);
        }
        sList = window.open('/adminsite/menu/menu.jsp?MODE=RUBRIQUE&CODE=' + value + '&PERMISSION=' + permission + '&RACINE=' + racine, 'menu2', 'scrollbars=yes, resizable=yes, status=yes, width=600, height=400, top=320, left=320');
    } else if (typeAide == 'groupe_dsi') {
        sList = window.open('/adminsite/menu/menu.jsp?MODE=GROUPE&CODE=' + value, 'menu2', 'scrollbars=yes, resizable=yes, status=yes, width=600, height=400, top=320, left=320');
    } else if (typeAide.indexOf('groupebo/') != -1) {
        var indexSlash1 = typeAide.indexOf('/');
        var indexSlash2 = typeAide.indexOf('/', indexSlash1 + 1);
        var indexSlash3 = typeAide.indexOf('/', indexSlash2 + 1);
        var permission = typeAide.substring(indexSlash1 + 1, typeAide.length);
        sList = window.open('/adminsite/menu/menu.jsp?MODE=GROUPE&CODE=' + value + '&PERMISSION=' + permission, 'menu2', 'scrollbars=yes, resizable=yes, status=yes, width=600, height=400, top=320, left=320');
    } else if (typeAide == 'public_vise_dsi') {
        sList = window.open('/adminsite/menu/menu.jsp?MODE=GROUPE&CODE=' + value + '&PUBLIC_VISE=1', 'menu2', 'scrollbars=yes, resizable=yes, status=yes, width=600, height=400, top=320, left=320');
    } else if (typeAide.indexOf('publicbo/') != -1) {
        var indexSlash1 = typeAide.indexOf('/');
        var indexSlash2 = typeAide.indexOf('/', indexSlash1 + 1);
        var indexSlash3 = typeAide.indexOf('/', indexSlash2 + 1);
        var permission = typeAide.substring(indexSlash1 + 1, typeAide.length);
        sList = window.open('/adminsite/menu/menu.jsp?MODE=GROUPE&CODE=' + value + '&PUBLIC_VISE=1&PERMISSION=' + permission, 'menu2', 'scrollbars=yes, resizable=yes, status=yes, width=600, height=400, top=320, left=320');
    } else if (typeAide.indexOf('fichefil') != -1) {
        var proc = typeAide.substring(typeAide.indexOf('fichefil') + 8, typeAide.length);
        sList = window.open("/servlet/com.jsbsoft.jtf.core.SG?PROC=" + proc + "&ACTION=RECHERCHER&TOOLBOX=LIEN_INTERNE_JOINTURE", "fichefil", "width=500,height=500,top=210,left=310,scrollbars=yes, resizable=yes, status=yes");
    } else if (typeAide == 'pagelibre') {
        sList = window.open("/servlet/com.jsbsoft.jtf.core.SG?PROC=SAISIE_PAGELIBRE&ACTION=RECHERCHER&TOOLBOX=LIEN_INTERNE_JOINTURE&LANGUE_FICHE=0", "pagelibre", "width=500,height=330,top=210,left=310, scrollbars=yes, resizable=yes, status=yes");
    } else if (typeAide == ('pagelibre_creation')) {
        field2 = "LIBELLE_CODE_PAGE_TETE";
        sList = window.open("/servlet/com.jsbsoft.jtf.core.SG?PROC=TRAITEMENT_PAGELIBRE&ACTION=AJOUTER&LANGUE=0&CODE_RUBRIQUE=" + f2, "pagelibre_creation&TOOLBOX=TRUE", "width=500,height=330,top=210,left=310, scrollbars=yes, resizable=yes, status=yes");
    } else if (typeAide == 'utilisateur') {
        sList = window.open("/servlet/com.jsbsoft.jtf.core.SG?PROC=SAISIE_UTILISATEUR&ACTION=RECHERCHER&MODE=RECHERCHE&TOOLBOX=TRUE", "utilisateur", "width=550, height=500, top=210, left=290, scrollbars=yes, resizable=yes, status=yes");
    } else if (typeAide == 'pagetete') {
        sList = window.open("/adminsite/toolbox/choix_objet.jsp?TOOLBOX=PAGE_TETE", "list", "width=520,height=440,top=10,left=100, scrollbars=yes, resizable=yes, status=yes");
        pageTete = true;
    } else if (typeAide == 'commentaire') {
        sList = window.open("/adminsite/toolbox/choix_objet.jsp?TOOLBOX=COMMENTAIRE", "list", "width=500,height=330,top=100,left=100, scrollbars=yes, resizable=yes, status=yes");
        pageTete = true;
    } else if (typeAide == 'requete') {
        field1 = "";
        field2 = f2;
        nomForm = "";
        fieldRequete = f1;
        if (field2 == 'STATS') {
            field2 = "";
            sList = window.open("/adminsite/toolbox/choix_objet.jsp?TOOLBOX=LIEN_REQUETE&RESTRICTION=STATS", "list", "width=500,height=330,top=100,left=100,scrollbars=yes,status=yes");
        } else {
            field2 = "";
            sList = window.open("/adminsite/toolbox/choix_objet.jsp?TOOLBOX=LIEN_REQUETE&RESTRICTION=XML", "list", "width=500,height=330,top=100,left=100,scrollbars=yes,status=yes");
        }
    }
}

function showMessageChamp(typeAide, f1, f2, nomFormulaire) {
    if (typeAide.indexOf('structure') != -1) {
        typeAide = 'strfo' + typeAide.substring(typeAide.indexOf('structure') + 9, typeAide.length);
    }
    showMessageField2(typeAide, f1, f2, nomFormulaire);
}

function ouvrirFenetreRechercheParProcessus(extension, processus, f1, f2) {
    field1 = f1;
    field2 = f2;
    nomForm = "";
    fieldRequete = "";
    texte = "";
    nomApplet = "";
    typeInsertion = "";
    nomFenetre = "";
    if (processus.indexOf('&') != -1) {
        nomFenetre = processus.substring(0, processus.indexOf('&'));
    }
    sList = window.open("/servlet/com.jsbsoft.jtf.core.SG?EXT=" + extension + "&PROC=" + processus + "&ACTION=RECHERCHER&TOOLBOX=LIEN_INTERNE_JOINTURE&LANGUE_FICHE=-1", "RECHERCHE_" + nomFenetre, "width=500, height=500, top=150, left=150, scrollbars=yes, resizable=yes, status=yes");
}

function showMessageTextArea(typeAide, langue, toolboxName) {
    nomApplet = "";
    typeInsertion = "";
    texte = toolboxName;
    field1 = "";
    field2 = "";
    nomForm = "";
    fieldRequete = "";
    if (typeAide == 'liste') {
        sList = window.open("/adminsite/toolbox/choix_objet.jsp?TOOLBOX=LIEN_REQUETE&LISTE_INCLUSE=1", "list", "width=500,height=330,top=100,left=100,scrollbars=yes,status=yes");
        typeInsertion = "liste";
    }
    if (typeAide == 'lien')
        sList = window.open("/adminsite/toolbox/choix_lien.jsp?LANGUE_FICHE=" + langue, "list", "width=500,height=330,top=100,left=100,scrollbars=yes,status=yes");
    if (typeAide == 'mailto')
        sList = window.open("/adminsite/toolbox/mailto.jsp?LANGUE_FICHE=" + langue, "list", "width=500,height=330,top=100,left=100,scrollbars=yes,status=yes");
    if (typeAide == 'image')
        sList = window.open("/servlet/com.jsbsoft.jtf.core.SG?PROC=SAISIE_MEDIA&ACTION=INSERER&TYPE_RESSOURCE=PHOTO", "photo", "width=650,height=535,top=20,left=100,scrollbars=yes");
}

function inBackOffice() {
    for (i = 0; i < window.document.forms.length; i++) {
        if ((window.document.forms[i].id != '') && (window.document.forms[i].id == 'form_saisie_front' || window.document.forms[i].id == 'recherche_avancee' || window.document.forms[i].id == nomForm))
            return false;
    }
    return true;
}

function effacerTextField(zoneSaisie, zoneLibelle, value, libelle) {
    if (inBackOffice()) {
        eval("window.document.forms[0]." + zoneSaisie + ".value = value;");
        eval("window.document.forms[0]." + zoneLibelle + ".value = libelle;");
        eval("window.document.forms[0]." + zoneLibelle + ".title = '';");
    } else {
        oForm = window.document.forms[nomForm];
        if (!oForm)
            nomForm = (window.document.forms['form_saisie_front']) ? 'form_saisie_front' : 'recherche_avancee';
        eval("window.document.forms['" + nomForm + "']." + zoneSaisie + ".value = value;");
        eval("window.document.forms['" + nomForm + "']." + zoneLibelle + ".value = libelle;");
        eval("window.document.forms['" + nomForm + "']." + zoneLibelle + ".title = '';");
    }
}

function effacerTextChamp(zoneSaisie, zoneLibelle, value, libelle, nomForm) {
    eval("window.document.forms['" + nomForm + "']." + zoneSaisie + ".value = value;");
    eval("window.document.forms['" + nomForm + "']." + zoneLibelle + ".value = libelle;");
}

function renvoyerValeurs(objet, code, libelle, sInfobulle) {
    if (window.opener && !window.opener.closed) {
        if (window.opener.liendanskt) {
            window.opener.modifieLien(objet, code, libelle);
        } else if (window.opener.field1 != "" || window.opener.field2 != "" || window.opener.fieldRequete != "") {
            window.opener.saveField(objet, code, libelle, sInfobulle);
        } else {
            window.opener.save(objet, code, libelle);
        }
    } else {
        if (typeof insertKListe_lienRequete == 'function') {
            insertKListe_lienRequete(objet, code, libelle);
        } else {
            if (window.parent.insertKListe_lienRequete) {
                window.parent.insertKListe_lienRequete(objet, code, libelle);
            } else {
                window.iFrameHelperCallback = function() {
                    if (window.iFrameRegistration) {
                        iFrameHelper.sendValues(window.iFrameRegistration, {
                            sCode: code,
                            libelle: libelle
                        });
                    }
                };
            }
        }
    }
    window.close();
}

function saveField(objet, code, libelle, sInfobulle) {
    if (inBackOffice()) {
        if (!pageTete) {
            if (field1 != "") {
                eval("window.document.forms[0]." + field1 + ".value = code;");
                var $kMonoSelect = jQuery('#kMonoSelect' + field1);
                if ($kMonoSelect.length > 0) {
                    $kMonoSelect.data('code', code);
                } else {
                    eval("jQuery(window.document.forms[0]." + field1 + ")").data('libelle', libelle).change();
                }
            }
            if (field2 != "") {
                var oLabelField = window.document.forms[0].elements[field2];
                if (oLabelField) {
                    oLabelField.value = libelle;
                    oLabelField.title = (sInfobulle ? sInfobulle : libelle);
                } else {
                    var $kMonoSelect = jQuery('#kMonoSelect' + field1).kMonoSelect();
                    if ($kMonoSelect) $kMonoSelect.value(libelle, (sInfobulle ? sInfobulle : libelle));
                }
            }
        } else {
            if (field1 != "")
                eval("window.document.forms[0]." + field1 + ".value = code+',TYPE='+objet;");
            if (field2 != "")
                eval("window.document.forms[0]." + field2 + ".value = objet + ' : ' +libelle;");
        }
    } else {
        oForm = window.document.forms[nomForm];
        if (!oForm)
            nomForm = (window.document.forms['form_saisie_front']) ? 'form_saisie_front' : 'recherche_avancee';
        if (field1 != "")
            eval("window.document.forms['" + nomForm + "']." + field1 + ".value = code;");
        if (field2 != "") {
            var oLabelField = window.document.forms[nomForm].elements[field2];
            oLabelField.value = libelle;
            oLabelField.title = (sInfobulle ? sInfobulle : libelle);
        }
    }
    if (fieldRequete != "") {
        chaine = "\"OBJET=" + objet;
        if (code.length > 0) {
            chaine = chaine + "&" + code;
        }
        chaine = chaine + "\"";
        eval("window.document.forms[0]." + fieldRequete + ".value = " + chaine);
    }
    field1 = "";
    field2 = "";
    nomForm = "";
    fieldRequete = "";
    texte = "";
    nomApplet = "";
    typeInsertion = "";
}

function renvoyerImage(id, height, width, alt, border, hspace, vspace, align) {
    if (window.opener && !window.opener.closed) {
        renvoyerImagePopup(id, height, width, alt, border, hspace, vspace, align)
    }
    window.close();
}

function renvoyerFormulaire(code, style, element) {
    if (window.opener && !window.opener.closed && window.opener.nomApplet != "") {
        window.opener.saveFormulaire(code, style, element);
    } else {
        window.parent.saveFormulaire(code, style, element);
    }
    window.close();
}

function renvoyerPageLibre(code, titre, langue) {
    if (window.opener && !window.opener.closed)
        window.opener.savePageLibre(code, titre, langue);
    window.close();
}

function renvoyerDocument(id, titre, NOM_FICHIER_JOINT, POIDS_FICHIER_JOINT, FORMAT_FICHIER_JOINT, PATH_FICHIER_JOINT, NOMFORM) {
    if (window.opener && !window.opener.closed) {
        if (window.opener.liendanskt)
            window.opener.modifieLien("", id, "");
        else if (window.opener.nomApplet != "")
            window.opener.saveDocument(id, titre, NOM_FICHIER_JOINT, POIDS_FICHIER_JOINT, FORMAT_FICHIER_JOINT, PATH_FICHIER_JOINT, NOMFORM);
        else
            window.opener.saveFormDocument(id, titre, NOM_FICHIER_JOINT, POIDS_FICHIER_JOINT, FORMAT_FICHIER_JOINT, PATH_FICHIER_JOINT, NOMFORM);
    }
    window.close();
}

function saveFormDocument(id, titre, NOM_FICHIER_JOINT, POIDS_FICHIER_JOINT, FORMAT_FICHIER_JOINT, PATH_FICHIER_JOINT, NOMFORM) {
    var nomForm = '0';
    if (NOMFORM)
        nomForm = NOMFORM;
    if (field1 != "") {
        eval("window.document.forms['" + nomForm + "']." + field1 + ".value = id;");
        eval("window.document.forms['" + nomForm + "']." + field2 + ".value = titre;");
        eval("window.document.forms['" + nomForm + "'].NOM_" + field1 + ".value = NOM_FICHIER_JOINT;");
        eval("window.document.forms['" + nomForm + "'].POIDS_" + field1 + ".value = POIDS_FICHIER_JOINT;");
        eval("window.document.forms['" + nomForm + "'].FORMAT_" + field1 + ".value = FORMAT_FICHIER_JOINT;");
        eval("window.document.forms['" + nomForm + "'].PATH_" + field1 + ".value = PATH_FICHIER_JOINT;");
    }
    if (texte != "") {
        var codeHtml = '<a';
        codeHtml += " href=\"[id-document];" + id + "[/id-document]\" >";
        codeHtml += titre + "</a>";
        insererTexte(texte, codeHtml);
    }
    field1 = "";
    field2 = "";
    nomForm = "";
    fieldRequete = "";
    texte = "";
    nomApplet = "";
}

function ouvrir_fen(url) {
    Xmas95 = new Date();
    secs = Xmas95.getSeconds();
    var name = "win" + secs;
    window.open(url, name, 'status=yes,toolbar=no,scrollbars=yes,width=600,height=550,resizable=yes');
}

function ouvrir_fen_web(url) {
    Xmas95 = new Date();
    secs = Xmas95.getSeconds();
    var name = "win" + secs;
    window.open(url, name, 'status=yes,menubar=yes, toolbar=yes, resizable=yes, scrollbars=yes, width=600, height=400');
}

function ouvrir_fen_x_y(url, x, y) {
    Xmas95 = new Date();
    secs = Xmas95.getSeconds();
    var name = "win" + secs;
    window.open(url, name, 'resizable=yes,status=yes,toolbar=no,scrollbars=yes,width=' + x + ',height=' + y);
}

function ouvrir_fen_w_h_name(url, width, height, name) {
    window.open(url, name, 'status=yes,toolbar=no,scrollbars=yes,width=' + width + ',height=' + height);
}

function nettoyerCodeHTML(s) {
    s2 = s;
    for (i = 0; i < s2.length; i++) {
        var charCode = s2.charCodeAt(i);
        if (charCode > 255) {
            if (charCode == 8217)
                s2 = s2.substring(0, i) + "'" + s2.substring(i + 1, s2.length);
            else if (charCode == 8364)
                s2 = s2.substring(0, i) + "&euro;" + s2.substring(i + 1, s2.length);
            else if (charCode == 8211)
                s2 = s2.substring(0, i) + "-" + s2.substring(i + 1, s2.length);
            else if (charCode == 8230)
                s2 = s2.substring(0, i) + "..." + s2.substring(i + 1, s2.length);
            else if (charCode == 339)
                s2 = s2.substring(0, i) + "&oelig;" + s2.substring(i + 1, s2.length);
            else
                s2 = s2.substring(0, i) + s2.substring(i + 1, s2.length);
            i = i - 1;
        }
    }
    if (s2 == '<p>&nbsp;</p>' || s2 == '<br />') {
        s2 = '';
    }
    return s2;
}
var arrayFichierMulti = new Array();
var libelleFichierMulti = "";
var arrayFichierUnique = new Array();
var libelleFichierUnique = "";
var espace = "";
var indiceForm = "";
var maxFileSize = "";
var noMulti = "";

function Fichier(p1, p2, p3, p4, p5) {
    this.id = p1;
    this.nom = p2;
    this.format = p3;
    this.version = p4;
    this.date = p5;
}

function ajouterFichier(no, mode, saisieFront) {
    var $ = jQuery,
        $this = $(this);
    sTypeRessource = "";
    sModeParameter = "";
    if (saisieFront == null)
        saisieFront = '';
    if (mode == 'multi') {
        indice = $('input[name="NB_' + libelleFichierMulti + '_' + no + '"]').val();
        $('input[name="NO_' + libelleFichierMulti + '_' + no + '"]').val(indice);
        sTypeRessource = $('input[name="MODE_' + libelleFichierMulti + '_' + no + '"]').val();
        sModeParameter = "&MODE_FICHIER=MULTIPLE";
        noMulti = no;
    } else {
        sTypeRessource = $('input[name="MODE_' + libelleFichierUnique + '_' + no + '"]').val();
        sModeParameter = "&MODE_FICHIER=UNIQUE";
        indice = no;
    }
    if (maxFileSize != '')
        sModeParameter += "&MAX_FILE_SIZE=" + maxFileSize;
    var popin = $.iframePopin({
        title: LOCALE_BO.popin.title.fichier,
        url: "/servlet/com.jsbsoft.jtf.core.SG?PROC=SAISIE_MEDIA&FCK_PLUGIN=TRUE&TYPE_RESSOURCE=" + sTypeRessource + "&ACTION=SELECTIONNER" + saisieFront + sModeParameter + "&NO_FICHIER=" + indice + "&ESPACE=" + espace + "",
        autoOpen: true,
        resizable: false,
        width: 800,
        onClose: function($iframe) {
            this.destroy();
        },
        buttons: {},
        onClose: function() {
            this.destroy();
        }
    });
    var registeredId = iFrameHelper.registeriFrame({
        onSendValues: function(file) {
            saveFormFichier(file.id, file.titre, file.format, file.legende, file.date_creation, file.num_fichier, file.mode_fichier);
            popin.destroy();
            iFrameHelper.unregisteriFrame(registeredId);
        },
        onAbort: function() {
            popin.destroy();
            iFrameHelper.unregisteriFrame(registeredId);
        },
        iFrame: popin.iFrame,
        caller: $this
    });
}

function supprimerFichier(no, indice) {
    reponse = confirm("Supprimer définitivement le fichier?");
    if (reponse) {
        arrayFichierMulti[no][indice] = null;
        preparerFichier(no);
    }
}

function renvoyerFichier(ID_FICHIER_JOINT, NOM_FICHIER_JOINT, FORMAT_FICHIER_JOINT, VERSION_FICHIER_JOINT, DATE_FICHIER_JOINT, NO_FICHIER_JOINT, MODE) {
    window.opener.saveFormFichier(ID_FICHIER_JOINT, NOM_FICHIER_JOINT, FORMAT_FICHIER_JOINT, VERSION_FICHIER_JOINT, DATE_FICHIER_JOINT, NO_FICHIER_JOINT, MODE);
    window.close();
}

function saveFormFichier(ID_FICHIER_JOINT, NOM_FICHIER_JOINT, FORMAT_FICHIER_JOINT, VERSION_FICHIER_JOINT, DATE_FICHIER_JOINT, NO_FICHIER_JOINT, MODE) {
    var $ = jQuery,
        fic = new Fichier(ID_FICHIER_JOINT, NOM_FICHIER_JOINT, FORMAT_FICHIER_JOINT, VERSION_FICHIER_JOINT, DATE_FICHIER_JOINT);
    if (MODE.indexOf('UNIQUE') != -1) {
        arrayFichierUnique[NO_FICHIER_JOINT] = fic;
        preparerFichierUnique(NO_FICHIER_JOINT);
    } else {
        var $multi = $('input[name="NB_' + libelleFichierMulti + '_' + noMulti + '"]');
        if ($multi.length > 0) {
            $multi.val($.parseInteger($multi.val()) + 1);
        }
        arrayFichierMulti[noMulti][NO_FICHIER_JOINT] = fic;
        preparerFichier(noMulti);
    }
}

function preparerFichier(no) {
    var $ = jQuery,
        temp = '';
    for (i = 0; i < arrayFichierMulti[no].length; i++) {
        if (arrayFichierMulti[no][i]) {
            if (temp.length > 0) {
                temp += "|";
            }
            temp += arrayFichierMulti[no][i].id + ";" + arrayFichierMulti[no][i].nom + ";" + arrayFichierMulti[no][i].format + ";" + arrayFichierMulti[no][i].version + ";" + arrayFichierMulti[no][i].date;
        }
    }
    $('input[name="TOTAL_' + libelleFichierMulti + '_' + no + '"]').val(temp);
    affichageFichier(no);
}

function affichageFichier(no) {
    var temp = '';
    var div = document.getElementById('inner-fichier-' + no);
    for (i = 0; i < arrayFichierMulti[no].length; i++) {
        if (arrayFichierMulti[no][i]) {
            temp += "<tr><td><input type=\"text\" class=\"libelle-fichier\" readonly=\"readonly\" name=\"LIBELLE_FICHIER_JOINT_" + i + "\" size=\"30\" value=\"" + arrayFichierMulti[no][i].nom + "\" />";
            temp += " <input type=\"text\" class=\"format-fichier\" readonly=\"readonly\" name=\"FORMAT_FICHIER_JOINT_" + i + "\" size=\"10\" value=\"" + arrayFichierMulti[no][i].format + "\" />";
            temp += " <input type=\"text\" class=\"date-fichier\" readonly=\"readonly\" name=\"DATE_FICHIER_JOINT_" + i + "\" size=\"6\" value=\"" + arrayFichierMulti[no][i].date + "\" />";
            temp += " <input type=\"button\" value=\"Supprimer\" onclick=\"supprimerFichier('" + no + "','" + i + "');\" /></td></tr>";
        }
    }
    div.innerHTML = temp.toString();
    noMulti = "";
}

function preparerFichierUnique(indice) {
    if (arrayFichierUnique[indice]) {
        fic = arrayFichierUnique[indice];
        temp = fic.id + ";" + fic.nom + ";" + fic.format + ";" + fic.version + ";" + fic.date;
        zoneFichier = libelleFichierUnique + '_' + indice;
        zoneLibelle = 'LIBELLE_' + libelleFichierUnique + '_' + indice;
        temp = temp.replace(/&apos;/g, "'");
        temp = temp.replace(/&quot;/g, "\\\"");
        nom = fic.nom.replace(/&apos;/g, "'");
        nom = nom.replace(/&quot;/g, "\\\"");
        eval("window.document.forms[indiceForm]." + zoneFichier + ".value = \"" + temp + "\";");
        eval("window.document.forms[indiceForm]." + zoneLibelle + ".value = \"" + nom + "\";");
    }
}

function effacerFichier(indice) {
    if (arrayFichierUnique[indice]) {
        arrayFichierUnique[indice] = null;
        zoneFichier = libelleFichierUnique + '_' + indice;
        zoneLibelle = 'LIBELLE_' + libelleFichierUnique + '_' + indice;
        eval("window.document.forms[indiceForm]." + zoneFichier + ".value = '';");
        eval("window.document.forms[indiceForm]." + zoneLibelle + ".value = 'Cliquer sur parcourir';");
    }
}
var arrayDossiergw = new Array();

function Dossiergw(p1, p2, p3, p4, p5) {
    this.id = p1;
    this.code = p2;
    this.parent = p3;
    this.nom = p4;
    this.espace = p5;
}

function visualiserDossiergw(idfiche, typefiche, espace) {
    window.open("/servlet/com.jsbsoft.jtf.core.SG?PROC=TRAITEMENT_DOSSIERGW_FRONT&ACTION=VISUALISER&SAISIE_FRONT=true&ESPACE=" + espace + "&ID_FICHE=" + idfiche + "&TYPE_FICHE=" + typefiche + "", "dossiergw", "width=450, height=400, scrollbars=yes, resizable=yes, status=no");
}

function ajouterDossiergw(sNomEspace) {
    if (d.aNodes[d.selectedNode]) {
        codeParent = d.aNodes[d.selectedNode].id;
        window.open("/servlet/com.jsbsoft.jtf.core.SG?PROC=TRAITEMENT_DOSSIERGW_FRONT&ACTION=INSERER&SAISIE_FRONT=true&ESPACE=" + sNomEspace + "&CODE_PARENT=" + codeParent + "", "Dossiergw", "width=400, height=200, status=no");
    } else {
        alert("Veuillez sélectionner un emplacement!");
    }
}

function supprimerDossiergw(sNomEspace) {
    if (d.aNodes[d.selectedNode]) {
        code = d.aNodes[d.selectedNode].id;
        if (code == 0) {
            alert("Vous ne pouvez pas supprimer la racine!");
        } else if (confirm('Suppression du dossier "' + d.aNodes[d.selectedNode].name + '". Etes-vous sûr ?')) {
            window.open("/servlet/com.jsbsoft.jtf.core.SG?PROC=TRAITEMENT_DOSSIERGW_FRONT&ACTION=SUPPRIMER&SAISIE_FRONT=true&ESPACE=" + sNomEspace + "&CODE_DOSSIER=" + code, "Dossiergw", "width=400, height=200, status=no");
        }
    } else {
        alert("Veuillez sélectionner un dossier!");
    }
}

function renommerDossiergw(sNomEspace) {
    if (d.aNodes[d.selectedNode]) {
        code = d.aNodes[d.selectedNode].id;
        if (code == 0) {
            alert("Vous ne pouvez pas modidier la racine!");
        } else {
            window.open("/servlet/com.jsbsoft.jtf.core.SG?PROC=TRAITEMENT_DOSSIERGW_FRONT&ACTION=MODIFIER&SAISIE_FRONT=true&ESPACE=" + sNomEspace + "&CODE_DOSSIER=" + code + "", "Dossiergw", "width=400, height=200, status=no");
        }
    } else {
        alert("Veuillez sélectionner un dossier!");
    }
}

function deplacerDossiergw(sNomEspace) {
    if (d.aNodes[d.selectedNode]) {
        code = d.aNodes[d.selectedNode].id;
        if (code == 0) {
            alert("Vous ne pouvez pas modidier la racine!");
        } else {
            window.open("/servlet/com.jsbsoft.jtf.core.SG?PROC=TRAITEMENT_DOSSIERGW_FRONT&ACTION=DEPLACER&SAISIE_FRONT=true&ESPACE=" + sNomEspace + "&CODE_DOSSIER=" + code + "", "Dossiergw", "width=400, height=400, resizable=yes, status=no");
        }
    } else {
        alert("Veuillez sélectionner un dossier!");
    }
}

function synchroniserDossiergw(nomForm) {
    if (!nomForm && indiceForm) {
        nomForm = indiceForm;
    }
    if (d.aNodes[d.selectedNode]) {
        code = d.aNodes[d.selectedNode].id;
        window.opener.arrayDossiergw = arrayDossiergw;
        window.close();
        if (code == 0) {
            window.opener.document.forms[nomForm].LIBELLE_DOSSIER.value = "Racine (par défaut)";
            window.opener.document.forms[nomForm].NOM_DOSSIER.value = "";
            window.opener.document.forms[nomForm].DOSSIER.value = "";
        } else {
            window.opener.document.forms[nomForm].DOSSIER.value = code;
            if (arrayDossiergw.length > 0) {
                for (i = 0; i < arrayDossiergw.length; i++) {
                    if (arrayDossiergw[i]) {
                        if (arrayDossiergw[i].code == code) {
                            window.opener.document.forms[nomForm].LIBELLE_DOSSIER.value = arrayDossiergw[i].nom;
                            window.opener.document.forms[nomForm].NOM_DOSSIER.value = arrayDossiergw[i].nom;
                        }
                    }
                }
            }
        }
    } else {
        window.opener.arrayDossiergw = arrayDossiergw;
        window.close();
        window.opener.document.forms[nomForm].DOSSIER.value = "";
        window.opener.document.forms[nomForm].LIBELLE_DOSSIER.value = "Racine (par défaut)";
        window.opener.document.forms[nomForm].NOM_DOSSIER.value = "";
    }
}

function affichageDtreeDossiergw(url) {
    if (url == '')
        url = '#';
    d = new dTree('d');
    d.add(0, -1, 'Racine', url, '', '', '/adminsite/utils/dtree/img/dd.gif', '/adminsite/utils/dtree/img/dd.gif');
    if (arrayDossiergw.length > 0) {
        for (i = 0; i < arrayDossiergw.length; i++) {
            if (arrayDossiergw[i]) {
                if (arrayDossiergw[i].parent != "") {
                    d.add(arrayDossiergw[i].code, arrayDossiergw[i].parent, arrayDossiergw[i].nom, url, '', '', '/adminsite/utils/dtree/img/folder.gif', '/adminsite/utils/dtree/img/folderopen.gif');
                } else {
                    d.add(arrayDossiergw[i].code, 0, arrayDossiergw[i].nom, url, '', '', '/adminsite/utils/dtree/img/folder.gif', '/adminsite/utils/dtree/img/folderopen.gif');
                }
            }
        }
    }
    document.write(d);
}

function popup_color_picker(nom1, nom2) {
    titreCouleur = nom1;
    titreExemple = nom2;
    var width = 400;
    var height = 260;
    window.open('/adminsite/utils/colpick/color_picker.jsp?COULEUR=' + titreCouleur + '&EXEMPLE=' + titreExemple + '&FORM=' + indiceForm + '', 'cp', 'resizable=no, location=no, width=' + width + ', height=' + height + ', menubar=no, status=yes, scrollbars=no, menubar=no');
}

function effacerCouleur(nom1, nom2) {
    window.document.forms[indiceForm].elements[nom2].style.borderColor = '';
    window.document.forms[indiceForm].elements[nom2].style.backgroundColor = '';
    window.document.forms[indiceForm].elements[nom1].value = '';
}
var arrayDocumentgw = new Array();

function Documentgw(p1, p2, p3, p4, p5) {
    this.id = p1;
    this.code = p2;
    this.url = p3;
    this.libelle = p4;
    this.dossier = p5
}

function affichageDtreeDocumentgw() {
    ddoc = new dTree('ddoc');
    ddoc.add(0, -1, 'Liste des fichiers', '#', '', '', '', '');
    if (arrayDossiergw.length > 0) {
        for (i = 0; i < arrayDossiergw.length; i++) {
            if (arrayDossiergw[i]) {
                if (arrayDossiergw[i].parent != "") {
                    ddoc.add(arrayDossiergw[i].code, arrayDossiergw[i].parent, arrayDossiergw[i].nom, '#', '', '', '/adminsite/utils/dtree/img/folder.gif', '/adminsite/utils/dtree/img/folderopen.gif');
                } else {
                    ddoc.add(arrayDossiergw[i].code, 0, arrayDossiergw[i].nom, '#', '', '', '/adminsite/utils/dtree/img/folder.gif', '/adminsite/utils/dtree/img/folderopen.gif');
                }
            }
        }
    }
    if (arrayDocumentgw.length > 0) {
        for (i = 0; i < arrayDocumentgw.length; i++) {
            if (arrayDocumentgw[i]) {
                ddoc.add(arrayDocumentgw[i].code, arrayDocumentgw[i].dossier, arrayDocumentgw[i].libelle, arrayDocumentgw[i].url, '', '', '/adminsite/utils/dtree/img/page.gif', '/adminsite/utils/dtree/img/page.gif');
            }
        }
    }
    document.write(ddoc);
}

function afficher_actions() {
    if (d.aNodes[d.selectedNode]) {
        code = d.aNodes[d.selectedNode].id;
        if (code == 0) {
            window.document.getElementById('folder_links').style.display = 'none';
            window.document.getElementById('root_links').style.display = 'inline';
        } else {
            window.document.getElementById('root_links').style.display = 'none';
            window.document.getElementById('folder_links').style.display = 'inline';
        }
    }
}

function selectionner_dossier() {
    oNode = d.aNodes[d.selectedNode];
    oForm = document.forms['form_saisie_front'];
    oForm.CODE_DOSSIER_PARENT.value = oNode.id;
    oForm.submit();
}

function replaceAll(str, search, repl) {
    while (str.indexOf(search) != -1)
        str = str.replace(search, repl);
    return str;
}
var INPUT_FIELD = 0;
var COMBO_BOX = 1;
var ZONE_FIELD = 2;

function MultivalueFieldItem(sCode, sLabel, sTitle) {
    this.sCode = sCode;
    if (sLabel == '') {
        this.sLabel = sCode;
    } else {
        this.sLabel = sLabel;
    }
    this.sTitle = sTitle;
    this.toString = function() {
        var s = 'sCode = ' + this.sCode;
        s += ', sLabel = ' + this.sLabel;
        if (this.sTitle) {
            s += ', sTitle = ' + this.sTitle;
        }
        return s;
    }
}

function MultivalueField(oForm, sName, iTypeField) {
    this.oForm = window.document.forms['form_saisie_front'] ? window.document.forms['form_saisie_front'] : oForm;
    this.sName = sName;
    this.iTypeField = iTypeField;
    this.aItemList = new Array();
    this.iSelectedItem = -1;
    this.Init = function() {
        this.oCodesHiddenField = this.oForm.elements[sName];
        this.oLabelsHiddenField = this.oForm.elements['LIBELLE_' + sName];
        this.oTitlesHiddenField = this.oForm.elements['INFOBULLE_' + sName];
        this.oField = this.oForm.elements['TMP_' + sName];
        if (this.iTypeField == ZONE_FIELD) {
            this.oLabelField = this.oForm.elements['LIBELLE_TMP_' + sName];
        }
        this.oSelect = this.oForm.elements['SELECT_' + sName];
        this.LoadData();
        if (this.aItemList.length > 0) {
            this.iSelectedItem = 0;
        }
        this.UpdateDisplay();
    }
    this.ContainsItem = function(oItem) {
        var inList = false;
        for (var i = 0; i < this.aItemList.length; i++) {
            if (oItem.sCode == this.aItemList[i].sCode) {
                inList = true;
            }
        }
        return inList;
    }
    this.Add = function() {
        var sCode = '';
        var sLabel = '';
        var sTitle;
        if (this.iTypeField == INPUT_FIELD) {
            sCode = this.oField.value;
        } else if (this.iTypeField == COMBO_BOX) {
            if (this.oField.selectedIndex > 0) {
                sCode = this.oField.value;
                sLabel = this.oField.options[this.oField.selectedIndex].text;
                if (sLabel.charAt(0) == '-') {
                    sCode = '';
                }
                sTitle = sLabel;
            }
        } else if (this.iTypeField == ZONE_FIELD) {
            sCode = this.oField.value;
            sLabel = this.oLabelField.value;
            sTitle = this.oLabelField.title;
        }
        if (sCode != '') {
            var oItem = new MultivalueFieldItem(sCode, sLabel, sTitle);
            if (!this.ContainsItem(oItem)) {
                this.aItemList[this.aItemList.length] = oItem;
                this.iSelectedItem = this.aItemList.length - 1;
                this.UpdateDisplay();
                this.SaveData();
                if (this.iTypeField == INPUT_FIELD) {
                    this.oField.value = '';
                } else if (this.iTypeField == COMBO_BOX) {
                    this.oField.selectedIndex = 0;
                }
            } else {
                alert('Cette valeur a déjà été insérée.');
            }
        }
    }
    this.Remove = function() {
        if (this.aItemList.length > 0) {
            if (this.iSelectedItem != -1) {
                for (var i = this.iSelectedItem; i < this.aItemList.length - 1; i++) {
                    this.aItemList[i] = this.aItemList[i + 1];
                }
                this.aItemList.length--;
                if (this.iSelectedItem == this.aItemList.length) {
                    this.iSelectedItem--;
                }
                this.UpdateDisplay();
                this.SaveData();
            } else {
                alert('Sélectionnez la valeur à supprimer.');
            }
        }
    }
    this.Modify = function() {
        if (this.aItemList.length > 0) {
            if (this.iSelectedItem != -1) {
                specificModifyItem(this);
            } else {
                alert('Sélectionnez la valeur à modifier.');
            }
        }
    }
    this.UpdateItem = function(item) {
        if (this.aItemList.length > 0) {
            if (this.iSelectedItem != -1) {
                this.aItemList[this.iSelectedItem] = item;
                this.UpdateDisplay();
                this.SaveData();
            } else {
                alert('Sélectionnez la valeur à modifier.');
            }
        }
    }
    this.MoveUp = function() {
        if (this.aItemList.length > 1 && this.iSelectedItem != -1 && this.iSelectedItem > 0) {
            var oItemTmp = this.aItemList[this.iSelectedItem];
            this.aItemList[this.iSelectedItem] = this.aItemList[this.iSelectedItem - 1];
            this.aItemList[this.iSelectedItem - 1] = oItemTmp;
            this.iSelectedItem--;
            this.UpdateDisplay();
            this.SaveData();
        }
    }
    this.MoveDown = function() {
        if (this.aItemList.length > 1 && this.iSelectedItem != -1 && this.iSelectedItem < this.aItemList.length - 1) {
            var oItemTmp = this.aItemList[this.iSelectedItem];
            this.aItemList[this.iSelectedItem] = this.aItemList[this.iSelectedItem + 1];
            this.aItemList[this.iSelectedItem + 1] = oItemTmp;
            this.iSelectedItem++;
            this.UpdateDisplay();
            this.SaveData();
        }
    }
    this.SelectItem = function() {
        if (this.aItemList.length == 0) {
            this.iSelectedItem = -1;
        } else {
            this.iSelectedItem = this.oSelect.selectedIndex;
        }
    }
    this.UpdateDisplay = function() {
        this.oSelect.options.length = 0;
        if (this.aItemList.length == 0) {
            this.oSelect.options[0] = new Option('--', -1);
            this.oSelect.selectedIndex = 0;
        } else {
            var oItem;
            for (var i = 0; i < this.aItemList.length; i++) {
                oItem = this.aItemList[i];
                this.oSelect.options[i] = new Option(oItem.sLabel);
                if (oItem.sTitle) {
                    this.oSelect.options[i].title = oItem.sTitle;
                }
            }
            this.oSelect.selectedIndex = this.iSelectedItem;
        }
    }
    this.LoadData = function() {
        if (this.oCodesHiddenField && this.oLabelsHiddenField) {
            var aCodes = this.oCodesHiddenField.value.split(';');
            var aLabels = this.oLabelsHiddenField.value.split(';');
            var aTitles;
            if (this.oTitlesHiddenField) {
                aTitles = this.oTitlesHiddenField.value.split(';');
            }
            for (var i = 0; i < aCodes.length; i++) {
                if (aCodes[i] != '' && aCodes[i] != '0000') {
                    if (aTitles) {
                        oItem = new MultivalueFieldItem(aCodes[i], aLabels[i], aTitles[i]);
                    } else {
                        oItem = new MultivalueFieldItem(aCodes[i], aLabels[i]);
                    }
                    if (!this.ContainsItem(oItem)) {
                        this.aItemList[this.aItemList.length] = oItem;
                    }
                }
            }
        }
    }
    this.SaveData = function() {
        this.oCodesHiddenField.value = '';
        this.oLabelsHiddenField.value = '';
        if (this.oTitlesHiddenField) {
            this.oTitlesHiddenField.value = '';
        }
        var oItem;
        for (var i = 0; i < this.aItemList.length; i++) {
            oItem = this.aItemList[i];
            if (i > 0) {
                this.oCodesHiddenField.value += ';';
                this.oLabelsHiddenField.value += ';';
                if (this.oTitlesHiddenField) {
                    this.oTitlesHiddenField.value += ';';
                }
            }
            this.oCodesHiddenField.value += oItem.sCode;
            this.oLabelsHiddenField.value += oItem.sLabel;
            if (this.oTitlesHiddenField) {
                this.oTitlesHiddenField.value += oItem.sTitle;
            }
        }
    }
}

function soumettreAjoutSousObjet(nomObjet) {
    nettoyerDonnees();
    window.document.forms[0].ACTION.value = 'NOCTRL_AJOUTER_' + nomObjet;
    window.document.forms[0].submit();
}

function soumettreModificationSousObjet(nomObjet, indice) {
    nettoyerDonnees();
    window.document.forms[0].ACTION.value = 'NOCTRL_MODIFIER_' + nomObjet + '#' + indice;
    window.document.forms[0].submit();
}

function soumettreSuppressionSousObjet(nomObjet, indice) {
    nettoyerDonnees();
    window.document.forms[0].ACTION.value = 'NOCTRL_SUPPRIMER_' + nomObjet + '#' + indice;
    window.document.forms[0].submit();
}

function soumettreValidationSousObjet(nomObjet) {
    nettoyerDonnees();
    window.document.forms[0].ACTION.value = 'VALIDER_' + nomObjet;
    window.document.forms[0].submit();
}

function soumettreAnnulationSousObjet(nomObjet) {
    nettoyerDonnees();
    window.document.forms[0].ACTION.value = 'NOCTRL_ANNULER_' + nomObjet;
    window.document.forms[0].submit();
}

function loadFckEditor() {}

function loading(input, idbody, idloader, submit) {
    input.style.display = 'none';
    window.document.getElementById(idloader).style.display = 'inline';
    if (submit)
        window.document.forms[0].submit();
    return true;
}
var formFileExtensions = {};

function checkFileExt(ctrl) {
    var file = eval("window.document.forms[0]." + ctrl + "_FILE.value;");
    var extensions = eval("formFileExtensions['" + ctrl + "']");
    var allowSubmit = false;
    if (!extensions || file == "") {
        return true;
    } else if (file != "") {
        type = file.slice(file.indexOf("\\") + 1);
        var ext = file.slice(file.lastIndexOf(".")).toLowerCase();
        for (var i = 0; i < extensions.length; i++) {
            if (extensions[i] == ext) {
                allowSubmit = true;
            }
        }
    }
    if (allowSubmit == false) {
        alert("L'extension du fichier " + ctrl.toLowerCase() + " est non valide (" + (extensions.join(", ")) + ")");
        return false;
    } else {
        return true
    }
    return allowSubmit;
}

function melA(mail, subject, body, domaine) {
    url = "mailto:" + mail + "@" + domaine;
    if (subject != '') {
        url += "?subject=" + subject;
    }
    if (body != '') {
        if (subject != '') {
            url += "&";
        } else {
            url += "?";
        }
        url += "body=" + body;
    }
    window.location.href = url;
};

function nettoyerDonnees() {
    if (document.getElementsByName('FCK_EDITORS_NAMES')[0]) {
        var editorsNames = document.getElementsByName('FCK_EDITORS_NAMES')[0].value.split(";");
        for (i = 0; i < editorsNames.length; i++) {
            if (editorsNames[i].length > 0) {
                try {
                    var editorName = editorsNames[i].substring(0, editorsNames[i].indexOf("_FCK"));
                    if (!(FCKeditorAPI.GetInstance(eval("'" + editorName + "'")).Status == FCK_STATUS_COMPLETE)) {
                        return false;
                        break;
                    }
                } catch (err) {
                    return false;
                }
            }
        }
    }
    return true;
}

function initFocus() {
    loadFckEditor();
}
var noeudA = null;
var noeudIMG = null;

function modifieLien(objet, code, libelle) {
    var urlsite = document.location.toString().substring(0, document.location.toString().indexOf("servlet/") + 8);
    var nouveauLien = "";
    if (objet == 'mailto')
        nouveauLien = urlsite + "[mailto]annuaire;" + code + "[/mailto]";
    else if (objet == 'mailto-url')
        nouveauLien = "mailto:" + code;
    else if (objet == 'url') {
        nouveauLien = code;
        if (nouveauLien.indexOf("#") != -1 && nouveauLien.indexOf("http://") == -1)
            nouveauLien = urlsite + "[id-ancre]" + code + "[/id-ancre]";
    } else if (objet == '') {
        nouveauLien = urlsite + "[id-document];" + code + "[/id-document]";
    } else if (objet == 'lien') {
        nouveauLien = code;
    } else
        nouveauLien = urlsite + "[id-fiche]" + objet + ";" + code + "[/id-fiche]";
    if (nouveauLien != null && nouveauLien != "") {
        noeudA.setAttribute("href", nouveauLien);
        noeudA.setAttribute("class", "ktoolbox");
    }
    liendanskt = false;
    noeudA = null;
}

function showLienDblClick(typePopup) {
    var langue = '0';
    if (ie) {
        if (typePopup == "lien") {
            liendanskt = true;
            window.open("/adminsite/toolbox/choix_lien.jsp?LANGUE_FICHE=" + langue + "&RENVOI=IN", "list", "width=500,height=330,top=100,left=100,scrollbars=yes,status=yes");
        } else if (typePopup == "mailto") {
            liendanskt = true;
            window.open("/adminsite/toolbox/mailto.jsp?LANGUE_FICHE=" + langue + "&RENVOI=IN", "list", "width=350,height=300,top=100,left=100,scrollbars=yes,status=yes");
        }
    }
}

function save(objet, code, libelle) {
    save2(objet, code, libelle, '');
}

function save2(objet, code, libelle, rubrique_forcage) {
    if (objet == 'tag' || objet == 'newsletter') {
        txtHtml = code;
        oEditor.FCK.InsertHtml(txtHtml);
    } else {
        var urlsite = document.location.toString().substring(0, document.location.toString().indexOf("servlet/") + 8);
        if (objet == 'url') {
            txtHtml = code;
            if (txtHtml.indexOf("#") != -1 && txtHtml.indexOf("http://") == -1)
                txtHtml = "[id-ancre]" + code + "[/id-ancre]";
        } else {
            txtHtml = "[id-fiche]" + objet + ";" + code + "[/id-fiche]";
            if (rubrique_forcage.length > 0) {
                if (txtHtml.indexOf("?") == -1) {
                    txtHtml += '?RF=' + rubrique_forcage;
                } else {
                    txtHtml += '&RF=' + rubrique_forcage;
                }
            }
        }
        window.parent.GetE("tag_kportal").value = txtHtml;
        window.parent.parent.Ok();
    }
    if (inBackOffice()) {
        if (field1 != "")
            eval("window.document.forms[0]." + field1 + ".value = code;");
        if (field2 != "")
            eval("window.document.forms[0]." + field2 + ".value = libelle;");
    } else {
        if (field1 != "")
            eval("window.document.forms['form_saisie_front']." + field1 + ".value = code;");
        if (field2 != "")
            eval("window.document.forms['form_saisie_front']." + field2 + ".value = libelle;");
    }
    if (texte != "") {
        if (typeInsertion == "liste") {
            codeHtml = "[traitement;requete;" + conversionRequete(objet, code) + "]";
        } else {
            var codeHtml = '<a ';
            if (objet == 'mailto')
                codeHtml += " href=\"[mailto]annuaire;" + code + "[/mailto]\" >" + libelle + "</a>";
            else if (objet == 'mailto-url')
                codeHtml += " href=\"mailto:" + code + "\" >" + libelle + "</a>";
            else if (objet == 'url')
                codeHtml += " href=\"" + code + "\" >" + libelle + "</a>";
            else if (objet == '')
                codeHtml += " href=\"[id-document]" + objet + ";" + code + "[/id-document]\" >" + libelle + "</a>";
            else
                codeHtml += " href=\"[id-fiche]" + objet + ";" + code + "[/id-fiche]\" >" + libelle + "</a>";
        }
        insererTexte(texte, codeHtml);
    }
    if (fieldRequete != "") {
        chaine = "\"OBJET=" + objet;
        if (code.length > 0)
            chaine = chaine + "&" + code;
        chaine = chaine + "\"";
        eval("window.document.forms[0]." + fieldRequete + ".value = " + chaine);
    }
    field1 = "";
    field2 = "";
    fieldRequete = "";
    texte = "";
    nomApplet = "";
    typeInsertion = "";
}

function renvoyerImagePopup(id, height, width, alt, border, hspace, vspace, align) {
    if (field1 != '') {
        saveImageField(id, alt);
    } else {
        var urlsite = document.location.toString();
        var url = urlsite.substring(0, urlsite.indexOf("servlet/") + 8) + "com.univ.utils.LectureImageToolbox?TAG=[id-image]" + id + "[/id-image]";
        if (id.indexOf("F") == -1) {
            alt = "[legende-image]" + id + "[/legende-image]";
        }
        if (nomApplet != '') {
            saveImageToolbox(url, height, width, alt, border, hspace, vspace, align);
        } else {
            saveImageEditeurJS(url, height, width, alt, border, hspace, vspace, align);
            window.opener.SetUrl(url, width, height, alt);
        }
    }
    field1 = '';
    field2 = '';
    nomForm = '';
    texte = '';
    nomApplet = '';
}

function saveImageField(id, alt) {
    if (field1 != '') {
        var oForm = (nomForm == '' ? window.document.forms[0] : window.document.forms[nomForm]);
        oForm[field1].value = id;
        oForm[field2].value = alt;
    }
}

function saveImageToolbox(url, height, width, alt, border, hspace, vspace, align) {
    if (testMode(nomApplet, true)) {
        if (moz) {
            var framounette = getThisDocumentContent(nomApplet);
            img = framounette.createElement('img');
            img.setAttribute('class', 'ktoolbox');
            img.setAttribute('src', url);
            var style = '';
            if (width != '') {
                style += 'width:' + width + 'px; ';
            }
            if (border && border != '') {
                style += 'border:' + border + 'px solid #000; ';
            }
            if ((vspace && vspace != '') || (hspace && hspace != '')) {
                style += 'margin:' + vspace + 'px ' + hspace + 'px;';
            }
            img.setAttribute('style', style);
            img.setAttribute('align', align);
            img.setAttribute('alt', alt);
            if (alt.length != 0) {
                img.setAttribute('title', alt);
            }
            insertNodeAtSelection(nomApplet, img);
        } else {
            var temp = '<img src="' + url + '"';
            temp += ' class="ktoolbox"';
            temp += ' style="';
            if (width != '') {
                temp += 'width: ' + width + 'px; ';
            }
            if (border != '') {
                temp += 'border: ' + border + 'px solid #000; ';
            }
            if ((vspace && vspace != '') || (hspace && hspace != '')) {
                temp += 'margin: ' + vspace + 'px ' + hspace + 'px;';
            }
            temp += '"';
            if (align != '') {
                temp += ' align="' + align + '"';
            }
            temp += ' alt="' + alt + '"';
            if (alt != '') {
                temp += ' title="' + alt + '"';
            }
            temp += ' />';
            insertHTML(nomApplet, temp);
        }
    }
}

function saveImageEditeurJS(url, height, width, alt, border, hspace, vspace, align) {
    if (texte != '') {
        var codeHtml = '<img ';
        codeHtml += ' alt="' + alt + '"';
        codeHtml += ' title="' + alt + '"';
        codeHtml += ' style="';
        codeHtml += 'width: ' + width + 'px; ';
        if (border != '') {
            codeHtml += 'border: ' + border + 'px solid #000; ';
        }
        if ((vspace && vspace != '') || (hspace && hspace != '')) {
            codeHtml += 'margin: ' + vspace + 'px ' + hspace + 'px;';
        }
        codeHtml += '"';
        codeHtml += ' align="' + align + '"';
        codeHtml += ' src="' + url + '" />';
        insererTexte(texte, codeHtml);
    }
}

function saveFormulaire(code, style) {
    if (nomApplet != "") {
        var codeHtml = "[formulaire;CODE=";
        codeHtml += code;
        codeHtml += "&STYLE=";
        codeHtml += style;
        codeHtml += "]";
        if (moz)
            insertNodeAtSelection(nomApplet, codeHtml);
        else {
            insertHTML(nomApplet, codeHtml);
            focusFrame(nomApplet);
        }
    }
    field1 = "";
    field2 = "";
    fieldRequete = "";
    texte = "";
    nomApplet = "";
}

function saveFormulaire(code, style, element) {
    var codeHtml = "";
    if (element == "0000") {
        codeHtml += "[formulaire;CODE=";
        codeHtml += code;
        codeHtml += "&STYLE=";
        codeHtml += style;
        codeHtml += "]";
    } else {
        codeHtml += "[cf;";
        codeHtml += code;
        codeHtml += ";";
        codeHtml += style;
        codeHtml += ";";
        codeHtml += element;
        codeHtml += "]";
    }
    oEditor.FCK.InsertHtml('<div>' + codeHtml + '</div>');
    field1 = "";
    field2 = "";
    fieldRequete = "";
    texte = "";
    nomApplet = "";
}

function saveDocument(id, titre, NOM_FICHIER_JOINT, POIDS_FICHIER_JOINT, FORMAT_FICHIER_JOINT, PATH_FICHIER_JOINT) {
    if (nomApplet != "") {
        var urlsite = document.location.toString().substring(0, document.location.toString().indexOf("servlet/") + 8);
        txtHtml = urlsite + "[id-document];" + id + "[/id-document]";
        cmdExec(nomApplet, 'CreateLink', txtHtml);
    }
    if (field1 != "") {
        eval("window.document.forms[0]." + field1 + ".value = id;");
        eval("window.document.forms[0]." + field2 + ".value = titre;");
        eval("window.document.forms[0].NOM_" + field1 + ".value = NOM_FICHIER_JOINT;");
        eval("window.document.forms[0].POIDS_" + field1 + ".value = POIDS_FICHIER_JOINT;");
        eval("window.document.forms[0].FORMAT_" + field1 + ".value = FORMAT_FICHIER_JOINT;");
        eval("window.document.forms[0].PATH_" + field1 + ".value = PATH_FICHIER_JOINT;");
    }
    if (texte != "") {
        var codeHtml = '<a';
        codeHtml += " href=\"[id-document];" + id + "[/id-document]\" >";
        codeHtml += titre + "</a>";
        insererTexte(texte, codeHtml);
    }
    field1 = "";
    field2 = "";
    fieldRequete = "";
    texte = "";
    nomApplet = "";
}

function savePageLibre(code, titre, langue) {
    if (field1 != "") {
        eval("window.document.forms[0]." + field1 + ".value = code+',LANGUE='+langue+',TYPE=pagelibre';");
        eval("window.document.forms[0]." + field2 + ".value = 'pagelibre : '+titre;");
    }
    field1 = "";
    field2 = "";
    fieldRequete = "";
    texte = "";
    nomApplet = "";
}

function renvoyerParametre(requete, nomParametre) {
    var regDiese = new RegExp("[#]+", "g");
    var res = "";
    var chaineARechercher = nomParametre + '=';
    chaineARechercher = chaineARechercher.toUpperCase();
    var tabDiese = requete.split(regDiese);
    for (var i = 0; i < tabDiese.length; i++) {
        var paramValue = tabDiese[i];
        if (paramValue.indexOf(nomParametre, 0) != -1) {
            res = paramValue.substring(nomParametre.length + 1);
        }
    }
    return res;
};

function VideoPlayer(elementContainerId, width, height, playerFlashURL) {
    this.elementContainerId = elementContainerId;
    this.stop = function() {};
    this.ajouterVideo = function(videoUrl, videoTitle) {};
    this.genererPlayer = function() {};
    this.lancerMedia = function(urlVideo, elementHTML) {};
    this.getVideoURLPlaylist = function() {};
    this.gererLanceur = function(elementHTML) {};
    this.isDisplayableURL = function(sURL) {
        return true;
    };
    this.setLoop = function(loop) {};
    this.setAutoplay = function(autoplay) {};
    this.setAutoload = function(autoload) {};
    this.setShowfullscreen = function(showfullscreen) {};
    this.setBuffer = function(buffer) {};
    this.setMargin = function(margin) {};
    this.setBgcolor = function(bgcolor) {};
    this.setBgcolor1 = function(bgcolor1) {};
    this.setBgcolor2 = function(bgcolor2) {};
    this.setPlayercolor = function(playercolor) {};
    this.setLoadingcolor = function(loadingcolor) {};
    this.setButtoncolor = function(buttoncolor) {};
    this.setButtonovercolor = function(buttonovercolor) {};
    this.setSlidercolor1 = function(slidercolor1) {};
    this.setSlidercolor2 = function(slidercolor2) {};
    this.setSliderovercolor = function(sliderovercolor) {};
    this.setShowstop = function(showstop) {};
    this.setShowvolume = function(showvolume) {};
    this.setShowtime = function(showtime) {};
    this.setShowplayer = function(showplayer) {};
    this.setTitlesize = function(titlesize) {};
    this.setTitlecolor = function(titlecolor) {};
    this.setShowiconplay = function(showiconplay) {};
    this.setIconplaycolor = function(iconplaycolor) {};
    this.setIconplaybgcolor = function(iconplaybgcolor) {};
    this.setIconplaybgalpha = function(iconplaybgalpha) {};
    this.setStartimage = function(startimage) {};
    this.setShowopen = function(showopen) {};
    this.setAutonext = function(autonext) {};
}

function AudioPlayer(elementContainerId, width, height, playerFlashURL) {
    this.stop = function() {};
    this.ajouterMedia = function(audioUrl, audioTitle) {};
    this.genererPlayer = function() {};
    this.lancerMedia = function(urlAudio, elementHTML) {};
    this.getAudioURLPlaylist = function() {};
    this.gererLanceur = function(elementHTML) {};
    this.setAutostart = function(autostart) {};
    this.setShowtime = function(showtime) {};
    this.setAutoreplay = function(autoreplay) {};
    this.setBgcolor = function(bgcolor) {};
};