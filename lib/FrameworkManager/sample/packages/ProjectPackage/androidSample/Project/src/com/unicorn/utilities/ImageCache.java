package com.unicorn.utilities;

import android.graphics.Bitmap;
import android.support.v4.util.LruCache;

/**
* 画像をメモリ上にキャッシュするクラスです
* キャッシュ容量は使用可能メモリの1/8で設定しています
* @author　c1363
*/
public final class ImageCache {

	private static final int MEM_CACHE_SIZE = 15 * 2 * 1024 * 1024; // 30MB

	private static LruCache<String, Bitmap> sLruCache;

	static {
		int maxMemory = (int) (Runtime.getRuntime().maxMemory() / 1024);
//		sLruCache = new LruCache<String, Bitmap>(MEM_CACHE_SIZE) {
		//使用可能メモリ
		sLruCache = new LruCache<String, Bitmap>(maxMemory/8) {
			@Override
			protected int sizeOf(String key, Bitmap bitmap) {
				return bitmap.getRowBytes() * bitmap.getHeight();
			}
		};
	}

	/**
	 * コンストラクタです
	 */
	private ImageCache() {
	}

	/**
	 * 渡されたkeyとbitmapで画像をキャッシュします
	 * @param key キャッシュするためのkeyです
	 * 文字列なら問題ありませんので画像urlを使用するのが便利です
	 * @bitmap 画像データです
	 */
	public static void setImage(String key, Bitmap bitmap) {
		if (getImage(key) == null) {
			sLruCache.put(key, bitmap);
		}
	}

	/**
	 * 渡されたkeyでキャッシュが存在する場合、キャッシュされた画像を返します
	 * @param キャッシュ検索するためのkey
	 * @return キャッシュに画像が存在する場合bitmapが返却されます
	 * キャッシュが無かった場合はnullが返却されます
	 */
	public static Bitmap getImage(String key) {
		return sLruCache.get(key);
	}
}