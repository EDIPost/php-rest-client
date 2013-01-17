<?php
    namespace EdipostService;

    interface iEdipostService{
        /**
        * Returns the default consignor for the customer. Most customers have only one consignor.
        *
        * @return the default consignor
        */
        public function getDefaultConsignor();


        /**
        * Creates a builder object used to build consignees
        *
        * @return a builder object
        */
        public function consigneeBuilder();


        /**
        * Get a consignee by it's ID.
        *
        * The ID can be found by searching for consignees by findConsignee, or when you create a new consignee
        *
        * @param consigneeID the ID of the consignee
        * @return a consignee
        */
        public function getConsignee( integer $consigneeID );


        /**
        * Search for consignees
        *
        * @param searchPhrase A phrase used when searching for consignees
        * @return a list of consignees
        */
        public function findConsignee( string $searchPhrase );


        /**
        * Creates a builder object used to build consignments
        * 
        * @return a builder object
        */
        public function consignmentBuilder();


        /**
        * Get a consignment by it's ID
        *
        * The ID can be found by searching for consignments by findConsignment, or when you create a new consignment
        *
        * @param consignmentID the ID of the consignment
        * @return a consignment
        */
        public function getConsignment( integer $consignmentID );


        /**
        * Search for consignments
        *
        * @param searchPhrase A phrase used when searching for consignments
        * @return a list of consignments
        */
        public function findConsignment( string $searchPhrase );

    }

    
    
    
    class EdipostService implements iEdipostService{

        


    }
?>
