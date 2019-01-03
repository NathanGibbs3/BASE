<?PHP
/*******************************************************************************
** Basic Analysis and Security Engine (BASE)
** Copyright (C) 2004 BASE Project Team
** Copyright (C) 2000 Carnegie Mellon University
**
** (see the file 'base_main.php' for license details)
**
** Project Lead: Kevin Johnson <kjohnson@secureideas.net>
**                Sean Muller <samwise_diver@users.sourceforge.net>
** Built upon work by Roman Danyliw <rdd@cert.org>, <roman@danyliw.com>
**
** Purpose: Capabilities registry to identify what functionality is available
**          on the currently running PHP install. This will allow us to vary
**          functionality on the fly. 
********************************************************************************
** Authors:
********************************************************************************
** Chris Shepherd <chsh@cogeco.ca>
**
********************************************************************************
*/

// Definitions for capabilities -- Add here as needed.
define(CAPA_MAIL, 1);
define(CAPA_PMAIL, 2);
define(CAPA_PEARDB, 3);
define(CAPA_PGRAPH, 4);

// Capabilities Registry class definition

class CapaRegistry
{
  var $CAPAREG;  // Registry hash, uses CAPA_ definitions as key.

  // Constructor
  function CapaRegistry ()
  {
    /* Automatically detect capabilities. Future development 
     * should be appended here.
     */
    
    // Mail
    if (function_exists('mail'))
    {
      $this->CAPAREG[CAPA_MAIL] = true;
    } else {
      $this->CAPAREG[CAPA_MAIL] = false;
    }      

    // PEAR::MAIL
    @include "Mail.php";
    if (class_exists("Mail"))
    {
      $this->CAPAREG[CAPA_PMAIL] = true;
    } else {
      $this->CAPAREG[CAPA_PMAIL] = false;
    }

    // PEAR::DB
    @include "DB.php";
    if (class_exists("DB"))
    {
      $this->CAPAREG[CAPA_PEARDB] = true;
    } else {
      $this->CAPAREG[CAPA_PEARDB] = false;
    }

    // PEAR::Image_Graph
    @include "Image_Graph.php";
    if (class_exists("Image_Graph"))
    {
      $this->CAPAREG[CAPA_PGRAPH] = true;
    } else {
      $this->CAPAREG[CAPA_PGRAPH] = false;
    }

    // Add checks here as needed.
    
  }

  // Capability checking function. Pass it the definitions used above.
  function hasCapa($capability) 
  {
    if (array_key_exists($capability, $this->CAPAREG))
    {
      return $this->CAPAREG[$capability];
    } else {
      return false;
    }
  }

}

$CAPAREG = new CapaRegistry();
?>
