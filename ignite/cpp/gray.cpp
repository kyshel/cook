#include <opencv2/opencv.hpp>
#include <iostream>

using namespace std;
using namespace cv;


int main(int argc, char** argv)
{
    String message;
    char* src_path = argv[1];
    char* dst_path = argv[2];

    Mat src_image;
    Mat dst_image;
    src_image = imread(src_path, 1);

    if (!src_image.data)
    {
        printf(" No image data \n ");
        return -1;
    }


    cvtColor(src_image, dst_image, CV_BGR2GRAY);
    message = imwrite(dst_path, dst_image) ? "OK" : "FAIL";
    cout << message ;

    return 0;
}
