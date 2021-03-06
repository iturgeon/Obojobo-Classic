<?php
require_once("../internal/app.php");

header('Content-Type: application/xml');

$title         = 'Obojobo';
$description   = "UCF's Learning Object System";
$launch_url    = \AppCfg::URL_WEB.'lti/assignment.php';
$platform      = 'canvas.instructure.com';
$privacy_level = 'public';
$picker_url    = \AppCfg::URL_WEB.'lti/picker.php';
$domain        = parse_url(\AppCfg::URL_WEB)['host'];
echo('<?xml version="1.0" encoding="UTF-8"?>')
?>
<cartridge_basiclti_link
	xmlns="http://www.imsglobal.org/xsd/imslticc_v1p0"
	xmlns:blti="http://www.imsglobal.org/xsd/imsbasiclti_v1p0"
	xmlns:lticm="http://www.imsglobal.org/xsd/imslticm_v1p0"
	xmlns:lticp="http://www.imsglobal.org/xsd/imslticp_v1p0"
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	xsi:schemaLocation="http://www.imsglobal.org/xsd/imslticc_v1p0 http://www.imsglobal.org/xsd/lti/ltiv1p0/imslticc_v1p0.xsd http://www.imsglobal.org/xsd/imsbasiclti_v1p0 http://www.imsglobal.org/xsd/lti/ltiv1p0/imsbasiclti_v1p0.xsd http://www.imsglobal.org/xsd/imslticm_v1p0 http://www.imsglobal.org/xsd/lti/ltiv1p0/imslticm_v1p0.xsd http://www.imsglobal.org/xsd/imslticp_v1p0 http://www.imsglobal.org/xsd/lti/ltiv1p0/imslticp_v1p0.xsd">
	<blti:title><?= $title ?></blti:title>
	<blti:description><?= $description ?></blti:description>
	<blti:launch_url><?= $launch_url ?></blti:launch_url>
	<blti:extensions platform="<?= $platform ?>">
		<lticm:property name="domain"><?= $domain ?></lticm:property>
		<lticm:property name="tool_id">grade_passback</lticm:property>
		<lticm:property name="privacy_level"><?= $privacy_level ?></lticm:property>
			<lticm:options name="resource_selection">
				<lticm:property name="url"><?= $picker_url ?></lticm:property>
				<lticm:property name="text"><?= $title ?></lticm:property>
				<lticm:property name="selection_width">700</lticm:property>
				<lticm:property name="selection_height">600</lticm:property>
				<lticm:property name="enabled">true</lticm:property>
			</lticm:options>
	</blti:extensions>
	<cartridge_bundle identifierref="BLTI001_Bundle"/>
	<cartridge_icon identifierref="BLTI001_Icon"/>
</cartridge_basiclti_link>