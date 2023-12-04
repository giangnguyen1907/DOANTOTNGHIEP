<?php
$ps_featureOptionFeatures = PreSchool::loadPsFeatureOptionFeature ();
if (isset ( $ps_featureOptionFeatures [$feature_option_feature->getType ()] ))
	echo __ ( $ps_featureOptionFeatures [$feature_option_feature->getType ()] );
