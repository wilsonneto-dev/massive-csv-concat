<?php

namespace Workana\Utils;

class CsvUtils
{
    public function concat ( 
        $files, 
        $output = 'output.csv' 
    )
    {
        $output = "./csv/output-".hash('ripemd160', "".time()).".txt";

        if(!is_array($files))
            throw new \Exception("The first parameter must to be an array");

        if(file_exists($output))
            throw new \Exception("The output file already exists");

        $f_handle_output = @fopen($output, 'wb');
        if(!$f_handle_output)
            throw new \Exception("Can't open the output file. File {$output}");

        $report = array();
        $report["inputs"] = array();
        $report["output"] = array();
        $report["output"]["file"] = $output;
        $report["output"]["count_lines"] = 0;

        foreach ($files as $file) 
        {
    
            $report_input_item["file"] = $file;
            $report_input_item["success"] = true;
            $report_input_item["error"] = null;
            $report_input_item["count_lines"] = 0;
            
            $first_line = true;
            
            try {

                if(file_exists($file))
                {
                    $f_handle_input = @fopen($file, 'rb');

                    while (($buffer = @fgets($f_handle_input)) !== false) 
                    {

                        $line = $buffer;
                        $line = trim($buffer);
                        
                        if(empty($line))
                            continue;

                        if($first_line)
                        { 
                            // if is the first line, skip header if not the firt file
                            $first_line = false;
                            
                            if($report["output"]["count_lines"] > 0)
                            { 
                                // if isn't the header of the output just skip it 
                                continue;
                            }
                        }

                        $writed = fwrite($f_handle_output, $line."\n");
                        
                        if($writed)
                        {
                            $report["output"]["count_lines"]++;
                        }
                        else
                        {
                            throw new \Exception("Writing function returned false.");
                        }

                        $report_input_item["count_lines"]++;

                    }
                
                    if (!feof($f_handle_input)) {
                        throw new \Exception(
                            "Writing error... The system interrupted the process before eof."
                        );
                    }
                    
                } else {
                    throw new \Exception("Input file doesn't exist.");
                }    

            } 
            catch (\Exception $ex) 
            {
                $report_input_item["success"] = false;
                $report_input_item["error"] = $ex->getMessage();
            }
            finally 
            {
                @fclose($f_handle_input);
                $report["inputs"][] = $report_input_item;
            }

        }
            
        return $report;

    }

    public static function _concat ($files, $output = 'output.csv')
    {
        $instance = new static();
        return $instance->concat($files, $output);
    }


}

?>