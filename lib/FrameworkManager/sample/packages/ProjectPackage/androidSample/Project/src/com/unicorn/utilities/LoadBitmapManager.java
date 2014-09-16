package com.unicorn.utilities;

import java.util.concurrent.BlockingQueue;
import java.util.concurrent.LinkedBlockingQueue;

import android.content.Context;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.os.Handler;
import android.os.Message;
import android.view.View;
import android.widget.ImageView;
import android.widget.ProgressBar;

/**
 * 画像ダウンローダークラス
 * ImageViewとurlを渡すとurlから画像を取得しImageViewにsetImageされます
 * ダウンロードスレッドはキューで管理されており、最大スレッド数はTHREAD_MAX_NUMで定義されます
 * @author　c1363
 */
public class LoadBitmapManager {

	private static final int THREAD_MAX_NUM = 3;

	private static BlockingQueue<LoadBitmapItem> downloadQueue;
	private static Handler handler;
	private static Context mContext;

	static {
		/*
		 * 画像情報を貯めるためのキュー
		 */
		downloadQueue = new LinkedBlockingQueue<LoadBitmapItem>();

		/*
		 * スレッド最大数まで画像ダウンロードスレッドを作成
		 */
		for (int i = 0; i < THREAD_MAX_NUM; i++) {
			new Thread(new DownloadWorker()).start();
		}

		/*
		 * 画像ダウンロード後にメッセージを受信するハンドラーを作成
		 */
		handler = new Handler() {
			@Override
			public void handleMessage(Message msg) {
				/*
				 * 取得したメッセージから画像情報を取得
				 */
				LoadBitmapItem item = (LoadBitmapItem) msg.obj;

				/*
				 * 画像ダウンロードがうまくいっていた場合はイメージビューに設定
				 */
				if (item.getBitmap() != null) {
					item.getImgView().setImageBitmap(item.getBitmap());
					item.getImgView().setVisibility(View.VISIBLE);
				}

				// プログレスバーを隠し、取得した画像を表示
				if (null != item.getProgress()) {
					item.getProgress().setVisibility(View.GONE);
				}
			}
		};
	}

	/**
	 * キューにたまったダウンロードスレッドを空にする
	 */
	public static void clearQueue() {
		downloadQueue.clear();
	}

	/**
	 * 引数として渡されたurlで画像をダウンロードしてImageViewに対して 画像を設定する。
	 * @param context Activityのコンテキストが入っています
	 * @param imgView　ダウンロードした画像をセットするImageViewが入っています
	 * @param progress　ダウンロード中にprogressバーを表示する場合はprogressバーを渡します
	 * @param url ダウンロードする画像のurlがはいっています
	 * @param isMask ダウンロードした画像をマスクする場合に使用します
	 * @param sideLength　ダウンロード後に画像を正方形にリサイズする場合縦横の長さを渡します
	 */
	public static void doDownloadBitmap(Context context, ImageView imgView, ProgressBar progress,
			String url, boolean isMask, int sideLength) {

		mContext = context;
		/*
		 * ダウンロードキューに入れる
		 */
		LoadBitmapItem item = new LoadBitmapItem();
		item.setImgView(imgView);
		item.setProgress(progress);
		item.setUrl(url);
		item.setMask(isMask);
		item.setSideLength(sideLength);
		downloadQueue.offer(item);

		return;
	}

	/**
	 * 実際に画像をダウンロードするワーカー
	 */
	private static class DownloadWorker implements Runnable {

		@Override
		public void run() {

			/*
			 * 画像ダウンロードスレッドは常に動き続けるから無限ループ
			 */
			for (;;) {
				Bitmap bitmap = null;
				LoadBitmapItem item;

				try {
					/*
					 * キューに値が入ったら呼び出される nullの状態ではwaitしている
					 */
					item = downloadQueue.take();
				} catch (Exception ex) {
					Log.e("ERROR", "", ex);
					continue;
				}

				/*
				 * ダウンロード
				 */
				try {
					bitmap = ImageCache.getImage(item.getUrl());
					if (bitmap == null) {
						bitmap = BitmapFactory.decodeStream(HttpClientAgent.getBufferedHttpEntity(
								mContext, item.getUrl()).getContent());

						if (bitmap != null) {
							// 取得した画像データをキャッシュに保持
							ImageCache.setImage(item.getUrl(), bitmap);
						}
					}
				} catch (Exception e) {

				}

				item.setBitmap(bitmap);

				/*
				 * 取得した画像情報でメッセージを作って投げる
				 */
				Message msg = new Message();
				msg.obj = item;
				handler.sendMessage(msg);
			}
		}
	}
}