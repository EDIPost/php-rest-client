<?php

    # http://code.google.com/p/lexa-xml-serialization/
    # MIT License
    # Generated on Sep 12, 2010 16:08

    namespace Lexa\XmlSerialization;

    class ClassMeta {
        private $shortName;
        private $namespace;
        private $xmlRoot;

        private $reflectors = array();
        private $props = array(); // [propName][valueType] -> array(xmlName, isElement)
        private $attrs = array(); // [attrName] -> array(propName, valueType)
        private $els = array();   // [elementName] -> array(propName, valueType)

        /** @param mixed $class An instance or a class name */
        public function __construct($class) {
            $r = new \ReflectionClass($class);
            $this->shortName = $r->getShortName();
            $this->namespace = $r->getNamespaceName();
            $this->xmlRoot = $this->receiveXmlRoot($r->getDocComment());

            $current = $r;
            while($current) {
                foreach($current->getProperties() as $p) {                    
                    if($p->getDeclaringClass()->name != $current->name)
                        continue;
                    $this->processProperty($p);           
                }
                $current = $current->getParentClass();
            }
        }

        private function receiveXmlRoot($docComment) {
            foreach(Annotation::parse($docComment) as $a) {
                if($a->getName() == "root" && $a->getParamCount() > 0)
                    return $a->getParam(0);
            }
        }

        private function processProperty(\ReflectionProperty $p) {
            $registered = false;
            foreach(Annotation::parse($p->getDocComment()) as $a) {
                if($a->getName() != "element" && $a->getName() != "attribute")
                    continue;

                if($p->isStatic())
                    $this->fail("Static property '{$p->name}' cannot be serialized");

                if(!$registered) {
                    $p->setAccessible(true);
                    $this->reflectors[$p->name] = $p;
                    $registered = true;
                }
                
                $type = "";
                if($a->getParamCount() > 0)
                    $type = $a->getParam(0);
                $type = $this->resolveType($type);

                $xmlName = $p->name;
                if($a->getParamCount() > 1)
                    $xmlName = $a->getParam(1);

                $isElement = $a->getName() == "element";

                if($this->hasXmlNameForProperty($p->name, $type))
                    $this->fail("Duplicate xml name for property name '{$p->name}' and value type '$type'");

                if(!array_key_exists($p->name, $this->props))
                    $this->props[$p->name] = array();
                $this->props[$p->name][$type] = array($xmlName, $isElement);

                if($isElement) {
                    if($this->hasPropertyForElement($xmlName))
                        $this->fail("Duplicate element '$xmlName'");
                    $this->els[$xmlName] = array($p->name, $type);
                } else {
                    if($this->hasPropertyForAttribute($xmlName))
                        $this->fail("Duplicate attribute '$xmlName'");
                    $this->attrs[$xmlName] = array($p->name, $type);
                }
            }
        }

        private function resolveType($type) {
            if(!$type || $type == "string")
                return "string";
            if($type == "int" || $type == "integer")
                return "integer";
            if($type == "bool" || $type == "boolean")
                return "boolean";
            if($type == "float" || $type == "double")
                return "double";
            if($type == "date" || $type == "datetime")
                return "DateTime";

            if(strpos($type, "\\") === false)
                $type = $this->getNamespace() . "\\" . $type;
            return ltrim($type, "\\");
        }

        function getClassName() {
            if($this->namespace)
                return $this->namespace . "\\" . $this->shortName;
            return $this->shortName;
        }

        function getNamespace() {
            return $this->namespace;
        }

        function getXmlRoot() {
            if(!$this->xmlRoot)
                return $this->shortName;
            return $this->xmlRoot;
        }

        function getPropertyNames() {
            return array_keys($this->props);
        }

        function getAttributeNames() {
            return array_keys($this->attrs);
        }

        function getAttributeNamesForProperty($propName) {
            $result = array();
            foreach($this->attrs as $attrName => $data) {
                if($data[0] == $propName)
                    $result[] = $attrName;
            }
            return $result;
        }

        function getElementNames() {
            return array_keys($this->els);
        }

        function getElementNamesForProperty($propName) {
            $result = array();
            foreach($this->els as $elementName => $data) {
                if($data[0] == $propName)
                    $result[] = $elementName;
            }
            return $result;
        }

        function getPropertyValue($obj, $propName) {
            return $this->reflectors[$propName]->getValue($obj);
        }

        function setPropertyValue($obj, $propName, $value) {
            $this->reflectors[$propName]->setValue($obj, $value);
        }

        function getAttributeName($propName, $valueType) {
            if(!$this->hasAttributeForProperty($propName, $valueType))
                return null;
            return $this->props[$propName][$valueType][0];
        }

        function getElementName($propName, $valueType) {
            if(!$this->hasElementForProperty($propName, $valueType))
                return null;
            return $this->props[$propName][$valueType][0];
        }

        function getPropertyNameForAttribute($attrName) {
            if(!$this->hasPropertyForAttribute($attrName))
                return null;
            return $this->attrs[$attrName][0];
        }

        function getPropertyNameForElement($elementName) {
            if(!$this->hasPropertyForElement($elementName))
                return null;
            return $this->els[$elementName][0];
        }

        function getPropertyTypeForAttribute($attrName) {
            if(!$this->hasPropertyForAttribute($attrName))
                return null;
            return $this->attrs[$attrName][1];
        }

        function getPropertyTypeForElement($elementName) {
            if(!$this->hasPropertyForElement($elementName))
                return null;
            return $this->els[$elementName][1];
        }

        private function hasXmlNameForProperty($propName, $valueType) {
            return array_key_exists($propName, $this->props) && array_key_exists($valueType, $this->props[$propName]);
        }

        private function hasAttributeForProperty($propName, $valueType) {
            if(!$this->hasXmlNameForProperty($propName, $valueType))
                return false;
            return !$this->props[$propName][$valueType][1];
        }

        private function hasElementForProperty($propName, $valueType) {
            if(!$this->hasXmlNameForProperty($propName, $valueType))
                return false;
            return $this->props[$propName][$valueType][1];
        }

        private function hasPropertyForAttribute($attrName) {
            return array_key_exists($attrName, $this->attrs);
        }

        private function hasPropertyForElement($elementName) {
            return array_key_exists($elementName, $this->els);
        }

        private function fail($message) {
            throw new \RuntimeException("Xml metadata error for {$this->getClassName()}: $message");
        }

    }

    class ClassMetaStore {
        private static $instance;

        /** @return ClassMetaStore */
        static function get() {
            if(!self::$instance)
                self::$instance = new ClassMetaStore();
            return self::$instance;
        }

        static function set(ClassMetaStore $store) {
            self::$instance = $store;
        }

        /** @return ClassMeta */
        static function getMeta($class) {
            if(is_object($class))
                $class = get_class($class);
            $store = self::get();
            $meta = $store->getMetaCore($class);
            if(!$meta) {
                $meta = new ClassMeta($class);
                $store->registerMeta($meta);
            }
            return $meta;
        }

        protected $data = array();

        protected function getMetaCore($className) {
            $key = $this->getKey($className);
            if(array_key_exists($key, $this->data))
                return $this->data[$key];
            return null;
        }

        protected function registerMeta(ClassMeta $meta) {
            $key = $this->getKey($meta->getClassName());
            $this->data[$key] = $meta;
        }

        protected function getKey($className) {
            return ltrim(strtolower($className), "\\");
        }

    }

    class XmlSerializer {

        // Serialization
        
        static function serialize($obj) {
            $doc = new \DOMDocument();
            $root = $doc->createElement(ClassMetaStore::getMeta($obj)->getXmlRoot());
            self::serializeObject($obj, $root);
            $doc->appendChild($root);
            return $doc;
        }

        private static function serializeObject($obj, \DOMElement $target) {
            $meta = ClassMetaStore::getMeta($obj);
            foreach($meta->getPropertyNames() as $propName) {
                $value = $meta->getPropertyValue($obj, $propName);

                if(is_array($value) || $value instanceof \Traversable) {
                    foreach($value as $key => $item) {
                        if(!is_int($key))
                            throw new \RuntimeException("Collections with associative indexing cannot be serialized");
                        self::serializeProperty($meta, $propName, $item, $target);
                    }
                } else {
                    self::serializeProperty($meta, $propName, $value, $target);
                }
            }
        }

        private static function serializeProperty(ClassMeta $meta, $propName, $value, \DOMElement $target) {
            if($value === null)
                return;
            
            $valueType = self::getValueType($value);

            $attrName = $meta->getAttributeName($propName, $valueType);
            if($attrName) {
                $target->setAttribute($attrName, self::formatAtomicValue($value));
            }

            $elementName = $meta->getElementName($propName, $valueType);
            if($elementName) {
                $child = $target->ownerDocument->createElement($elementName);
                if(self::isObject($value)) {
                     self::serializeObject($value, $child);
                } else {
                    $text = self::formatAtomicValue($value);
                    $child->appendChild($target->ownerDocument->createTextNode($text));
                }
                $target->appendChild($child);
            }

            if(!$attrName && !$elementName)
                throw new \RuntimeException("Don't know how to serialize value of type '$valueType' for property '$propName' of class '{$meta->getClassName()}'");
        }

        private static function getValueType($value) {
            if(is_object($value))
                return get_class($value);
            return gettype($value);
        }

        private static function isObject($value) {
            return is_object($value) && !($value instanceof \DateTime);
        }

        private static function formatAtomicValue($value) {
            if(is_bool($value))
                return $value ? "true" : "false";

            if($value instanceof \DateTime) {
                $result = $value->format("Y-m-d");
                $time = $value->format("H:i:s");
                if($time != "00:00:00")
                    $result .= " $time";
                return $result;
            }

            return (string)$value;
        }
       
        // Unserialization

        static function unserialize(\DOMDocument $doc, $className) {            
            $result = new $className;
            self::unserializeObject($result, $doc->documentElement);
            return $result;
        }


        private static function unserializeObject($obj, \DOMElement $source) {
            $meta = ClassMetaStore::getMeta($obj);

            $bag = array();

            foreach($source->attributes as $attribute) {
                $propName = $meta->getPropertyNameForAttribute($attribute->name);
                if(!$propName)
                    continue;
                $valueType = $meta->getPropertyTypeForAttribute($attribute->name);
                $value = self::parseAtomicValue($attribute->value, $valueType);
                self::addPropertyToBag($propName, $value, $bag);
            }

            foreach ($source->childNodes as $child) {
                if(!($child instanceof \DOMElement))
                    continue;
                $propName = $meta->getPropertyNameForElement($child->tagName);
                if(!$propName)
                    continue;
                $valueType = $meta->getPropertyTypeForElement($child->tagName);
                $isObject = !self::isAtomicType($valueType);
                $value = $isObject
                    ? new $valueType
                    : self::parseAtomicValue(trim($child->textContent), $valueType);
                self::addPropertyToBag($propName, $value, $bag);
                if($isObject)
                    self::unserializeObject($value, $child);
            }

            foreach($bag as $propName => $data) {
                $currentValue = $meta->getPropertyValue($obj, $propName);
                if(is_array($currentValue)) {
                    if(is_array($data)) {
                        $meta->setPropertyValue($obj, $propName, array_merge($currentValue, $data));
                    } else {
                        array_push($currentValue, $data);
                        $meta->setPropertyValue($obj, $propName, $currentValue);
                    }
                } elseif($currentValue instanceof \ArrayAccess) {
                    if(is_array($data)) {
                        foreach($data as $item)
                            $currentValue[] = $item;
                    } else {
                        $currentValue[] = $data;
                    }
                } else {
                    $meta->setPropertyValue($obj, $propName, is_array($data) ? $data[count($data) - 1] : $data);
                }
            }
        }

        private static function addPropertyToBag($name, $value, array &$bag) {
            if(!array_key_exists($name, $bag)) {
                $bag[$name] = $value;
            } else {
                if(is_array($bag[$name])) {
                    array_push($bag[$name], $value);
                } else {
                    $bag[$name] = array($bag[$name], $value);
                }
            }
        }

        private static function isAtomicType($type) {
            return $type == "string"
                || $type == "integer"
                || $type == "boolean"
                || $type == "double"
                || $type == "DateTime";
        }

        private static function parseAtomicValue($value, $type) {
            if($type == "integer")
                return intval($value);

            if($type == "boolean")
                return strtolower($value) == "true" || intval($value) > 0;

            if($type == "double")
                return doubleval($value);

            if($type == "DateTime")
                return new \DateTime($value);
            
            return $value;
        }

        // Schema generation

        static function generateSchema($className) {
            $g = new SchemaGenerator();
            return $g->generate($className);
        }


    }


    class Annotation {

        /** @return array */
        static function parse($docComment) {
            preg_match_all("/\\@xml(element|attribute|root)\\s*(?:\\((.*?)\\))?/is", $docComment, $matches, PREG_SET_ORDER);
            $result = array();
            foreach($matches as $match) {
                $annotation = new Annotation;
                $annotation->name = strtolower($match[1]);
                if(count($match) > 2)
                    $annotation->params = preg_split("/\\s*\\,\\s*/", trim($match[2]), -1, PREG_SPLIT_NO_EMPTY);
                $result[] = $annotation;
            }
            return $result;
        }

        private $name;
        private $params;

        protected function __construct() {
        }

        function getName() {
            return $this->name;
        }

        function getParamCount() {
            return count($this->params);
        }

        function getParam($index) {
            return $this->params[$index];
        }

    }

    class SchemaGenerator {        
        private $pendingTypes;
        private $generatedTypes;

        function generate($rootClassName) {
            $doc = new \DOMDocument();

            $root = $doc->createElement("xs:schema");
            $root->setAttribute("xmlns:xs", "http://www.w3.org/2001/XMLSchema");

            $meta = ClassMetaStore::getMeta($rootClassName);

            $element = $doc->createElement("xs:element");
            $element->setAttribute("name", $meta->getXmlRoot());
            $element->setAttribute("type", $this->getXsdType($meta->getClassName()));
            $root->appendChild($element);

            $this->pendingTypes = array($meta->getClassName() => 1);
            $this->generatedTypes = array();

            while(count($this->pendingTypes)) {
                reset($this->pendingTypes);
                $type = key($this->pendingTypes);
                $this->generatedTypes[$type] = 1;
                unset($this->pendingTypes[$type]);

                $root->appendChild($this->createSchemaNodeForObject($doc, new $type));
            }

            $doc->appendChild($root);
            return $doc;
        }

        private function createSchemaNodeForObject(\DOMDocument $doc, $obj) {
            $meta = ClassMetaStore::getMeta($obj);

            $complexType = $doc->createElement("xs:complexType");
            $complexType->setAttribute("name", $this->getXsdType($meta->getClassName()));

            $sequence = $doc->createElement("xs:sequence");
            $attributeBag = array();

            foreach($meta->getPropertyNames() as $propName) {
                $elementNames = $meta->getElementNamesForProperty($propName);
                if(count($elementNames) > 0) {

                    $value = $meta->getPropertyValue($obj, $propName);
                    $isCollection = is_array($value) || $value instanceof \Traversable;


                    $propertyElement = count($elementNames) == 1
                        ? $this->createSchemaNodeForSingleElementProperty($doc, $meta, $elementNames[0])
                        : $this->createSchemaNodeForMultiElementProperty($doc, $meta, $elementNames);
                    
                    if($isCollection) {
                        $collectionChoice = $doc->createElement("xs:choice");
                        $collectionChoice->appendChild($propertyElement);
                        $collectionChoice->setAttribute("minOccurs", 0);
                        $collectionChoice->setAttribute("maxOccurs", "unbounded");
                        $propertyElement = $collectionChoice;
                    }
                    $sequence->appendChild($propertyElement);                    
                }

                $attrNames = $meta->getAttributeNamesForProperty($propName);
                foreach($attrNames as $attrName) {
                    $type = $meta->getPropertyTypeForAttribute($attrName);
                    $this->mentionType($type);
                    $attribute = $doc->createElement("xs:attribute");
                    $attribute->setAttribute("name", $attrName);
                    $attribute->setAttribute("type", $this->getXsdType($type));
                    $attributeBag[] = $attribute;
                }
            }

            if($sequence->hasChildNodes())
                $complexType->appendChild($sequence);

            foreach($attributeBag as $attribute)
                $complexType->appendChild($attribute);

            return $complexType;
        }

        private function createSchemaNodeForSingleElementProperty(\DOMDocument $doc, ClassMeta $meta, $elementName) {
            $result = $doc->createElement("xs:element");
            $result->setAttribute("name", $elementName);

            $type = $meta->getPropertyTypeForElement($elementName);
            $this->mentionType($type);
            $result->setAttribute("type", $this->getXsdType($type));

            $result->setAttribute("minOccurs", 0);
            return $result;
        }

        private function createSchemaNodeForMultiElementProperty(\DOMDocument $doc, ClassMeta $meta, array $names) {
            $result = $doc->createElement("xs:choice");
            foreach($names as $name)
                $result->appendChild($this->createSchemaNodeForSingleElementProperty($doc, $meta, $name));
            $result->setAttribute("minOccurs", 0);
            return $result;
        }

        private function getXsdType($type) {
            if($type == "integer" || $type == "double" || $type == "boolean" || $type == "string")
                return "xs:$type";
            if($type == "DateTime")
                return "xs:string";

            return str_replace("\\", "-", $type) . "-Type";
        }

        private function mentionType($type) {
            if($type == "string" || $type == "integer" || $type == "boolean" || $type == "double" || $type == "DateTime")
                return;

            if(!array_key_exists($type, $this->generatedTypes))
                $this->pendingTypes[$type] = 1;
        }


    }
