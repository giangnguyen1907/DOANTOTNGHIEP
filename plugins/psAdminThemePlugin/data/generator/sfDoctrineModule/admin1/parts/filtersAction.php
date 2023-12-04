  protected function getFilters() {
    
    $filters = $this->getUser()->getAttribute('<?php echo $this->getModuleName() ?>.filters', $this->configuration->getFilterDefaults(), 'admin_module');

    if (isset($filters['ps_customer_id'])) {
      myUser :: setPscustomerID($filters['ps_customer_id']);
    } else {
        myUser :: setPscustomerID(null);
    }
    
    return $filters;    
  }

  protected function setFilters(array $filters) {
  	return $this->getUser()->setAttribute('<?php echo $this->getModuleName() ?>.filters', $filters, 'admin_module');
  }
