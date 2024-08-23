<?php include('includes/header.php'); ?>

<?php 

$sharedConfig = [
    'region' => 'us-east-1',
    'version' => 'latest'
];

$sdk = new Aws\Sdk($sharedConfig); /*Instantiate SDK class with configs for API use*/

$s3Client = $sdk->createS3(); /*creates the S3 Client for API use*/

?>

<?php
    if($_SERVER["REQUEST_METHOD"] === "POST") {

        if(isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
    
            try {

    
                $file_name = $_FILES['file']['name']; /*file name e.g. name.jpg */
    
                $file_tmp_name = $_FILES['file']['tmp_name']; /*!!! IMPORTANT - this is what you need to supply the SourceFile to properly upload the file*/
    
                $file_type = $_FILES['file']['type']; /*file type*/
    
                $result = $s3Client->putObject([
                    'Bucket' => $bucket,
                    'Key' =>   $file_name,
                    'SourceFile' => $file_tmp_name,
                    'ContentType'=>$file_type,
                    'ContentDisposition'=>'attachment'
                ]);

                $contents = $s3Client->listObjectsV2([
                    'Bucket' => $bucket,
                ]);
    
                // echo "Archivo subido";
                $_SESSION['message'] = 'Archivo subido';
                $_SESSION['message_type'] = 'success';
            }
            catch(Exception $e) {
                echo $e;
            }
        }
    }
?>

  
<div class="container mt-5">
        
        <div class="row justify-content-center">
            <div class="col-md-6">

            <?php if (isset($_SESSION['message'])) { ?>

                <div class="alert alert-<?= $_SESSION['message_type']?> alert-dismissible fade show" role="alert">
                    <?= $_SESSION['message']?>

                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

            <?php session_unset(); } ?>

            <div class="card card-body">
                        <h5 class="card-title">Seleccionar archivo</h5>
                        <form method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <input class="form-control"  type="file" name="file" id="anyfile">
                            </div>
                            <input type="submit" class="btn btn-primary" value="Subir" name="submit">
                        </form>
                    </div>
   
            </div>
        </div>

        <h3 class="mt-5">Archivos almacenados en la nube</h3>
 
        <table class="mt-5 table table-bordered">
            <thead>
                <tr>
                    <th>Archivo</th>
                    <th>Descargar</th>
                </tr>
            </thead>
            <tbody>

            <?php 
        try {
            // var_dump($contents);
            $contents = $s3Client->listObjectsV2([
                'Bucket' => $bucket,
            ]);
            // echo "The contents of your bucket are: \n";
            foreach ($contents['Contents'] as $content) { 
                
                // echo $content['Key'] . "\n";

                $command = $s3Client->getCommand('GetObject', [
                    'Bucket' => $bucket,
                    'Key'    => $content['Key']
                ]);
                
                $request = $s3Client->createPresignedRequest($command, '+1 hour');
                $signedUrl = (string) $request->getUri();
                
                // echo $signedUrl . "\n"; // Enlace de descarga pre-firmado

        ?>

                <tr>
                    <td><?php echo $content['Key']; ?></td>
                    <td class="col-2 text-truncate">
                        <a href="<?php echo $signedUrl; ?>">
                            <i class="fas fa-download"></i>
                        </a>
                    </td>
                </tr>
            
        <?php 
            }
        } catch (Exception $exception) {
            echo "Failed to list objects in $bucket with error: " . $exception->getMessage();
            exit("Please fix error with listing objects before continuing.");
        }
        ?>
                
            </tbody>
        </table>

        
</div>

