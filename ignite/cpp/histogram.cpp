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



	vector<Mat> bgr_planes;
	split(src_image, bgr_planes);
	int histSize = 256;
	float range[] = { 0, 256 };
	const float* histRange = { range };
	bool uniform = true; bool accumulate = false;
	Mat b_hist, g_hist, r_hist;
	calcHist(&bgr_planes[0], 1, 0, Mat(), b_hist, 1, &histSize, &histRange, uniform, accumulate);
	calcHist(&bgr_planes[1], 1, 0, Mat(), g_hist, 1, &histSize, &histRange, uniform, accumulate);
	calcHist(&bgr_planes[2], 1, 0, Mat(), r_hist, 1, &histSize, &histRange, uniform, accumulate);
	// Draw the histograms for B, G and R
	int hist_w = 512; int hist_h = 400;
	int bin_w = cvRound((double)hist_w / histSize);
	Mat histImage(hist_h, hist_w, CV_8UC3, Scalar(0, 0, 0));
	normalize(b_hist, b_hist, 0, histImage.rows, NORM_MINMAX, -1, Mat());
	normalize(g_hist, g_hist, 0, histImage.rows, NORM_MINMAX, -1, Mat());
	normalize(r_hist, r_hist, 0, histImage.rows, NORM_MINMAX, -1, Mat());
	for (int i = 1; i < histSize; i++)
	{
		line(histImage, Point(bin_w*(i - 1), hist_h - cvRound(b_hist.at<float>(i - 1))),
			Point(bin_w*(i), hist_h - cvRound(b_hist.at<float>(i))),
			Scalar(255, 0, 0), 2, 8, 0);
		line(histImage, Point(bin_w*(i - 1), hist_h - cvRound(g_hist.at<float>(i - 1))),
			Point(bin_w*(i), hist_h - cvRound(g_hist.at<float>(i))),
			Scalar(0, 255, 0), 2, 8, 0);
		line(histImage, Point(bin_w*(i - 1), hist_h - cvRound(r_hist.at<float>(i - 1))),
			Point(bin_w*(i), hist_h - cvRound(r_hist.at<float>(i))),
			Scalar(0, 0, 255), 2, 8, 0);
	}

	is_debug ? showImageInWindow(histImage, "window_a") : (void(0));
	message = imwrite(dst_path, histImage) ? "OK" : "FAIL";
	cout << message ;

	return 0;
}

void showImageInWindow(Mat image, String window_name = "window_a") {
	namedWindow(window_name, WINDOW_AUTOSIZE); // Create a window for display.
	imshow(window_name, image);                // Show our image inside it.
	waitKey(0); // Wait for a keystroke in the window
}