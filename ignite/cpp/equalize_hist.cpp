#include <opencv2/opencv.hpp>
#include <iostream>

using namespace std;
using namespace cv;

void showImageInWindow(Mat, String);

int main(int argc, char** argv)
{
	String message;
	const String keys =
		"{help h usage ? |      | print help   }"
		"{@src_image     |      | src_image   }"
		"{@dst_image     |      | dst_image   }"
		"{D debug        |      | 0-no, 1-yes }"
		;
	CommandLineParser parser(argc, argv, keys);
	if (parser.has("help"))
	{
		parser.printMessage();
		return 0;
	}
	if (!parser.check())
	{
		parser.printErrors();
		return 0;
	}

	String src_path = parser.get<String>(0);
	String dst_path = parser.get<String>(1);
	bool is_debug = parser.has("debug") ? true : false;


	Mat src_image, dst_image, gray_image;

	src_image = imread(src_path, 1);
	if (!src_image.data)
	{
		printf(" No image data \n ");
		return -1;
	}


	cvtColor(src_image, gray_image, CV_BGR2GRAY);
	equalizeHist(gray_image, dst_image);

	is_debug ? showImageInWindow(dst_image, "window_a") : (void(0));
	message = imwrite(dst_path, dst_image) ? "OK" : "FAIL";
	cout << message ;

	return 0;
}

void showImageInWindow(Mat image, String window_name = "window_a") {
	namedWindow(window_name, WINDOW_AUTOSIZE); // Create a window for display.
	imshow(window_name, image);                // Show our image inside it.
	waitKey(0); // Wait for a keystroke in the window
}