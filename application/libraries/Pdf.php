<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * CodeIgniter DomPDF Library
 *
 * Generate PDF's from HTML in CodeIgniter
 *
 * @packge        CodeIgniter
 * @subpackage        Libraries
 * @category        Libraries
 * @author        Ardianta Pargo
 * @license        MIT License
 * @link        https://github.com/ardianta/codeigniter-dompdf
 */

use Dompdf\Dompdf;
use Dompdf\FontMetrics;

class Pdf extends Dompdf
{
  /**
   * PDF filename
   * @var String
   */
  public $filename;
  public function __construct()
  {
    parent::__construct();
    $this->filename = "laporan.pdf";
  }
  /**
   * Get an instance of CodeIgniter
   *
   * @access    protected
   * @return    void
   */
  protected function ci()
  {
    return get_instance();
  }
  /**
   * Load a CodeIgniter view into domPDF
   *
   * @access    public
   * @param    string    $view The view to load
   * @param    array    $data The view data
   * @return    void
   */
  public function load_view($view, $data = array(), $image = ['url' => '', 'width' => '', 'height' => '', 'opacity' => ''], $output = false)
  {
    $html = $this->ci()->load->view($view, $data, TRUE);
    $this->load_html($html);
    // Render the PDF
    $this->render();

    if ($image['url'] != '' && $image['width'] != '' && $image['height'] != '' && $image['opacity'] != '') {
      $canvas = $this->getCanvas();
      $w = $canvas->get_width();
      $h = $canvas->get_height();

      $imageURL = $image['url'];
      $imgWidth = $image['width'];
      $imgHeight = $image['height'];
      $canvas->set_opacity($image['opacity']);
      $x = (($w - $imgWidth) / 2);
      $y = (($h - $imgHeight) / 2);


      $canvas->image($imageURL, $x, $y, $imgWidth, $imgHeight);
    }
    // Output the generated PDF to Browser
    if ($output) {
      return $this->output();
    } else {
      $this->stream($this->filename, array("Attachment" => false));
    }
  }
}
